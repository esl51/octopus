<?php

namespace Tests\Feature;

use Tests\ItemTest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UserTest extends ItemTest
{
    protected $dummyRole;
    protected $validStructure = [
        'data' => [
            'id',
            'name',
            'email',
            'roles',
        ],
    ];
    protected $uri = '/api/access/users';
    protected $class = User::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->dummyRole = Role::factory()->create([
            'name' => 'Test Role',
        ]);
        $this->dummyData = [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret00',
            'password_confirmation' => 'secret00',
        ];
    }

    protected function createItem()
    {
        $item = call_user_func([$this->class, 'factory']);
        $item = $item->create();
        $item->syncRoles([$this->dummyRole->id]);
        return $item;
    }

    /** @test */
    public function create_item()
    {
        $dummyData = $this->dummyData;
        $dummyData['roles'] = [$this->dummyRole->id];
        $this->actingAs($this->user)
            ->postJson($this->uri, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure)
            ->assertJsonCount(1, 'data.roles');

        unset($dummyData['password']);
        unset($dummyData['password_confirmation']);
        unset($dummyData['roles']);
        $this->assertDatabaseHas($this->tableName, $dummyData);
    }

    /** @test */
    public function update_item()
    {
        $item = $this->createItem();
        $dummyData = $this->dummyData;
        $dummyData['roles'] = [$this->dummyRole->id];
        $this->actingAs($this->user)
            ->putJson($this->uri . '/' . $item->id, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure)
            ->assertJsonCount(1, 'data.roles');

        unset($dummyData['password']);
        unset($dummyData['password_confirmation']);
        unset($dummyData['roles']);
        $this->assertDatabaseHas($this->tableName, $dummyData);
    }

    /** @test */
    public function list_items_by_role()
    {
        $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '/?role=' . Role::findByName('root')->id)
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]]);
    }
}
