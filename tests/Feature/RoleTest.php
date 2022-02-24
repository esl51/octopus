<?php

namespace Tests\Feature;

use App\Models\Permission;
use Tests\ItemTest;
use App\Models\Role;

class RoleTest extends ItemTest
{
    protected $dummyPermission;
    protected $validStructure = [
        'data' => [
            'id',
            'name',
            'guard_name',
            'permissions',
        ],
    ];
    protected $uri = '/api/access/roles';
    protected $class = Role::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->dummyPermission = Permission::factory()->create([
            'name' => 'Test Permission',
        ]);
        $this->dummyData = [
            'name' => 'Test Role',
            'guard_name' => 'api',
        ];
    }

    protected function createItem()
    {
        $item = call_user_func([$this->class, 'factory']);
        $item = $item->create();
        $item->syncPermissions([$this->dummyPermission->id]);
        return $item;
    }

    /** @test */
    public function create_item()
    {
        $dummyData = $this->dummyData;
        $dummyData['permissions'] = [$this->dummyPermission->id];
        $this->actingAs($this->user)
            ->postJson($this->uri, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure)
            ->assertJsonCount(1, 'data.permissions');

        $this->assertDatabaseHas($this->tableName, $this->dummyData);
    }

    /** @test */
    public function update_item()
    {
        $item = $this->createItem();
        $dummyData = $this->dummyData;
        $dummyData['permissions'] = [$this->dummyPermission->id];
        $this->actingAs($this->user)
            ->putJson($this->uri . '/' . $item->id, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure)
            ->assertJsonCount(1, 'data.permissions');

        $this->assertDatabaseHas($this->tableName, $this->dummyData);
    }
}
