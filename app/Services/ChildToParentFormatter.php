<?php namespace App\Services;

use Illuminate\Support\Collection;

class ChildToParentFormatter
{
    /** @var Collection|Entity[] */
    private $entityCollection;

    public function __construct()
    {
        $this->entityCollection = new Collection();
    }

    /**
     * @param array $entitiesData
     * @return Collection|Entity[]
     * @throws Exceptions\HierarchyLoopException
     */
    public function getEntitiesFromInput(array $entitiesData) : array
    {
        foreach ($entitiesData as $childName => $parentName) {
            $parentEntity = $this->setParentEntity($parentName);
            $this->setChildEntity($childName, $parentEntity);
        }

        return $this->entityCollection->all();
    }

    /**
     * @param string $parentName
     * @return Entity
     */
    private function setParentEntity(string $parentName) : Entity
    {
        $parentEntity = $this->entityCollection->get($parentName);
        if ($parentEntity === null) {
            $parentEntity = new Entity($parentName);
            $this->entityCollection->put($parentName, $parentEntity);
        }

        return $parentEntity;
    }

    /**
     * @param string $childName
     * @param Entity $parentEntity
     * @throws Exceptions\HierarchyLoopException
     */
    private function setChildEntity(string $childName, Entity $parentEntity) : void
    {
        /** @var Entity $childEntity */
        $childEntity = $this->entityCollection->get($childName);
        if ($childEntity === null) {
            $childEntity = new Entity($childName, $parentEntity);
            $this->entityCollection->put($childName, $childEntity);
        } else if ($childEntity->getParent() === null) {
            /** @var Entity $grandParent */
            $grandParent = $parentEntity->getParent();
            if ($grandParent && $grandParent->getName() === $childName) {
                throw new Exceptions\HierarchyLoopException('Loop occurred in the input');
            }

            // Do not add as parent if they are their own parent so they'd be on the top of the tree map
            if ($childEntity->getName() !== $parentEntity->getName()) {
                $childEntity->setParent($parentEntity);
            }
        }
    }
}
