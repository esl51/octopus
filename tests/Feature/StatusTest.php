<?php

namespace Tests\Feature;

use Tests\ItemTest;
use App\Models\Status;

class StatusTest extends ItemTest
{
    protected $validStructure = [
        'data' => [
            'id',
            'name',
            'variant',
            'is_published',
            'is_default',
        ],
    ];
    protected $uri = '/api/statuses';
    protected $class = Status::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->dummyData = [
            'variant' => 'warning',
            'is_published' => 0,
            'is_default' => 0,
        ];
        $this->dummyTranslatableData = [
            config('app.locale') => [
                'name' => 'Test Status',
            ],
        ];
    }

    /** @test */
    public function list_items_sorted_by_name()
    {
        $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '?sort_by=name&sort_desc=0')
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]]);
    }
}
