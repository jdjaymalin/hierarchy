<?php namespace App\Services;

class HierarchyService
{
    /** @var array */
    private $allEntitiesMap = [];

    /** @var array */
    private $hierarchyMap = [];

    /**
     * @param Entity[] $entities
     * @return array
     */
    public function generateHierarchyMap(array $entities) : array
    {
        foreach ($entities as $entity) {
            if ($this->isEntityNotInMap($entity)) {
                $this->allEntitiesMap[$entity->getName()] = [];
            }

            $parent = $entity->getParent();
            if ($parent !== null) {
                $this->allEntitiesMap[$parent->getName()][][$entity->getName()]
                    =& $this->allEntitiesMap[$entity->getName()];
            } else {
                $this->hierarchyMap[$entity->getName()] =& $this->allEntitiesMap[$entity->getName()];
            }
        }

        return $this->hierarchyMap;
    }

    /**
     * @param Entity $entity
     * @return bool
     */
    private function isEntityNotInMap(Entity $entity) : bool
    {
        return isset($this->allEntitiesMap[$entity->getName()]) === false;
    }
}
