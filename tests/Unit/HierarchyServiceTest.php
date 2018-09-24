<?php namespace Tests\Unit;

use App\Services\Entity;
use App\Services\HierarchyService;
use Tests\TestCase;

class HierarchyServiceTest extends TestCase
{
    /** @var HierarchyService */
    private $hierarchyService;

    public function setUp() : void
    {
        parent::setUp();
        $this->hierarchyService = new HierarchyService();
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function getEntities(array $entityData = []) : array
    {
        $a = new Entity('A');
        $b = new Entity('B', $a);
        $c = new Entity('C', $b);
        $d = new Entity('D', $c);
        $e = new Entity('E', $c);

        $entityArray = array_merge([
            $a,
            $b,
            $c,
            $d,
            $e
        ], $entityData);

        return $entityArray;
    }

    /**
     * @return array
     */
    private function expectedResponse() : array
    {
        return [
            'A' => [[
                'B' => [[
                    'C' => [
                        ['D' => []],
                        ['E' => []]
                    ]
                ]]
            ]]
        ];
    }

    /**
     * @covers \App\Services\HierarchyService::generateHierarchyMap()
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGenerateHierarchyMap() : void
    {
        $map = $this->hierarchyService->generateHierarchyMap($this->getEntities());

        $this->assertEquals($map, $this->expectedResponse());
    }

    /**
     * @covers \App\Services\HierarchyService::generateHierarchyMap()
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGenerateHierarchyMapForOnlyTwoEntities() : void
    {
        $supervisor = new Entity('Parent');
        $subordinate = new Entity('Child', $supervisor);
        $expectedOutput = [
            'Parent' => [['Child' => []]]
        ];

        $map = $this->hierarchyService->generateHierarchyMap([$subordinate, $supervisor]);

        $this->assertEquals($map, $expectedOutput);
    }

    /**
     * @covers \App\Services\HierarchyService::generateHierarchyMap()
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testGenerateHierarchyMapForOneEntity() : void
    {
        $self = new Entity('Self');
        $expectedOutput = [
            'Self' => []
        ];

        $map = $this->hierarchyService->generateHierarchyMap([$self]);

        $this->assertEquals($map, $expectedOutput);
    }
}
