<?php

namespace Tests;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\PermissionTablesSeeder;

abstract class ItemTest extends TestCase
{
    protected $user;
    protected $dummyData = [];
    protected $dummyTranslatableData = [];
    protected $validStructure;
    protected $uri;
    protected $class;
    protected $tableName;
    protected $translationsTableName;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionTablesSeeder::class);
        $this->user = User::factory()->afterCreating(function ($model) {
            $model->assignRole('root');
        })->create();
        $model = (new $this->class());
        $this->tableName = $model->getTable();
        if (method_exists($model, 'translations')) {
            $translationModelName = $model->getTranslationModelName();
            $this->translationsTableName = (new $translationModelName())->getTable();
        }
    }

    protected function createItem()
    {
        $item = call_user_func([$this->class, 'factory']);
        $this->actingAs($this->user);
        return $item->create();
    }

    /** @test */
    public function show_item()
    {
        $item = $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '/' . $item->id)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure);
    }

    /** @test */
    public function list_items()
    {
        $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri)
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]]);
    }

    /** @test */
    public function list_items_sorted_by_id()
    {
        $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '?sort_by=id&sort_desc=0')
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]]);
    }

    /** @test */
    public function search_item_by_id()
    {
        $item = $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '?search=' . $item->id)
            ->assertSuccessful()
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]]);
    }

    /** @test */
    public function create_item()
    {
        $dummyData = $this->dummyData;
        if (!empty($this->dummyTranslatableData)) {
            $dummyData['translations'] = $this->dummyTranslatableData;
        }
        $this->actingAs($this->user)
            ->postJson($this->uri, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure);

        $this->assertDatabaseHas($this->tableName, $this->dummyData);

        if ($this->dummyTranslatableData) {
            foreach ($this->dummyTranslatableData as $locale => $dummyTranslatableItem) {
                $dummyTranslatableData = $dummyTranslatableItem;
                $dummyTranslatableData['locale'] = $locale;
                $this->assertDatabaseHas($this->translationsTableName, $dummyTranslatableData);
            }
        }
    }

    /** @test */
    public function update_item()
    {
        $item = $this->createItem();
        $dummyData = $this->dummyData;
        if (!empty($this->dummyTranslatableData)) {
            $dummyData['translations'] = $this->dummyTranslatableData;
        }
        $this->actingAs($this->user)
            ->putJson($this->uri . '/' . $item->id, $dummyData)
            ->assertSuccessful()
            ->assertJsonStructure($this->validStructure);

        $this->assertDatabaseHas($this->tableName, $this->dummyData);

        if ($this->dummyTranslatableData) {
            foreach ($this->dummyTranslatableData as $locale => $dummyTranslatableItem) {
                $dummyTranslatableData = $dummyTranslatableItem;
                $dummyTranslatableData['locale'] = $locale;
                $this->assertDatabaseHas($this->translationsTableName, $dummyTranslatableData);
            }
        }
    }

    /** @test */
    public function delete_item()
    {
        $item = $this->createItem();
        $this->actingAs($this->user)
            ->deleteJson($this->uri . '/' . $item->id)
            ->assertSuccessful();

        $this->assertDatabaseMissing($this->tableName, $this->dummyData);

        if ($this->dummyTranslatableData) {
            foreach ($this->dummyTranslatableData as $locale => $dummyTranslatableItem) {
                $dummyTranslatableData = $dummyTranslatableItem;
                $dummyTranslatableData['locale'] = $locale;
                $this->assertDatabaseMissing($this->translationsTableName, $dummyTranslatableData);
            }
        }
    }
}
