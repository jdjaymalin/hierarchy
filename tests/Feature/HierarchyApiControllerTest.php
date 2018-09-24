<?php namespace Tests\Feature;

use Tests\TestCase;

class HierarchyApiControllerTest extends TestCase
{
    /**
     * @covers \App\Http\Controllers\HierarchyApiController::getHierarchy
     */
    public function testGetHierarchy() : void
    {
        $jsonData = ['data' => '{"D": "C","E": "C","C": "B","B": "A"}'];
        $response = $this->post('/api/hierarchy', $jsonData);

        $response->assertStatus(200);
        $response->assertSee('result');
    }

    /**
     * @covers \App\Http\Controllers\HierarchyApiController::getHierarchy
     */
    public function testGetHierarchyNoData() : void
    {
        $jsonData = [];
        $response = $this->post('/api/hierarchy', $jsonData);

        $response->assertStatus(422);
        $response->assertSee('errors');
    }

    /**
     * @covers \App\Http\Controllers\HierarchyApiController::getHierarchy
     */
    public function testGetHierarchyNotJson() : void
    {
        $jsonData = ['data' => 'Invalid Json'];
        $response = $this->post('/api/hierarchy', $jsonData);

        $response->assertStatus(422);
        $response->assertSee('errors');
    }

    /**
     * @covers \App\Http\Controllers\HierarchyApiController::getHierarchy
     */
    public function testGetHierarchyInvalidJson() : void
    {
        $jsonData = ['data' => '{"Pete": "Nick",}'];
        $response = $this->post('/api/hierarchy', $jsonData);

        $response->assertStatus(422);
        $response->assertSee('errors');
    }

    /**
     * @covers \App\Http\Controllers\HierarchyApiController::getHierarchy
     */
    public function testGetHierarchyEmptyJson() : void
    {
        $jsonData = ['data' => '{}'];
        $response = $this->post('/api/hierarchy', $jsonData);

        $response->assertStatus(200);
        $response->assertSee('result');
    }
}
