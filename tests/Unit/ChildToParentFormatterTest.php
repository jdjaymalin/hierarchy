<?php namespace Tests\Unit;

use App\Services\ChildToParentFormatter;
use App\Services\Exceptions\HierarchyLoopException;
use Tests\TestCase;

class ChildToParentFormatterTest extends TestCase
{
    /** @var ChildToParentFormatter */
    private $childToParent;

    public function setUp() : void
    {
        parent::setUp();
        $this->childToParent = new ChildToParentFormatter();
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function getEntityData(array $entityData = []) : array
    {
        return array_merge([
            'A' => 'B',
            'C' => 'D',
            'D' => 'E',
            'E' => 'B'
        ], $entityData);
    }

    /**
     * @covers \App\Services\ChildToParentFormatter::getEntitiesFromInput
     * @throws HierarchyLoopException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetEntities() : void
    {
        $entities = $this->childToParent->getEntitiesFromInput($this->getEntityData());

        $this->assertNotEmpty($entities);
        $this->assertCount(5, $entities);
    }

    /**
     * @covers \App\Services\ChildToParentFormatter::getEntitiesFromInput
     * @throws HierarchyLoopException
     */
    public function testGetEntitiesWhenThereIsLoop() : void
    {
        $this->expectException(HierarchyLoopException::class);

        $this->childToParent->getEntitiesFromInput(
            $this->getEntityData(['B' => 'E'])
        );
    }

    /**
     * @covers \App\Services\ChildToParentFormatter::getEntitiesFromInput
     * @throws HierarchyLoopException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetEntitiesWithEmptyInput() : void
    {
        $entities = $this->childToParent->getEntitiesFromInput([]);

        $this->assertEmpty($entities);
    }

    /**
     * @covers \App\Services\ChildToParentFormatter::getEntitiesFromInput
     * @throws HierarchyLoopException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGetEntitiesWithEntityIsOwnParent() : void
    {
        $entities = $this->childToParent->getEntitiesFromInput(['Jade' => 'Jade']);

        $this->assertNotEmpty($entities);
        $this->assertCount(1, $entities);
    }
}
