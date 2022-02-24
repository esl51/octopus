<?php

namespace Tests\Feature;

use Tests\ItemTest;
use App\Models\Page;
use App\Models\User;
use App\Models\Status;
use Database\Seeders\StatusesTableSeeder;

class PageTest extends ItemTest
{
    protected $publishedStatus;
    protected $defaultStatus;
    protected $validStructure = [
        'data' => [
            'id',
            'slug',
            'title',
            'headline',
            'body',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'status_id',
            'author_id',
        ],
    ];
    protected $uri = '/api/pages';
    protected $class = Page::class;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusesTableSeeder::class);
        $this->publishedStatus = Status::getPublished();
        $this->defaultStatus = Status::getDefault();
        $this->dummyData = [
            'status_id' => $this->defaultStatus->id,
            'author_id' => $this->user->id,
        ];
        $this->dummyTranslatableData = [
            config('app.locale') => [
                'slug' => 'test-post',
                'title' => 'Test Post',
                'headline' => 'Test Post Headline',
                'body' => '<p>The body of the simple text post.</p>',
                'meta_title' => 'Meta Title',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'meta keywords',
            ],
        ];
    }

    /** @test */
    public function user_can_list_only_published_pages()
    {
        $this->createItem();
        $this->actingAs(User::factory()->create())
            ->getJson($this->uri)
            ->assertSuccessful()
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function root_can_list_unpublished_pages()
    {
        $this->createItem();
        $this->actingAs($this->user)
            ->getJson($this->uri . '?sort_by=status&sort_desc=0')
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['*' => $this->validStructure['data']]])
            ->assertJsonPath('data.0.status_id', strval($this->defaultStatus->id));
    }

    /** @test */
    public function can_generate_slug()
    {
        $title = ' This is 00w-m+the test POST!!! ';
        $slug = 'this-is-00w-m-the-test-post';
        $this->actingAs($this->user)
            ->getJson($this->uri . '/slug?title=' . $title)
            ->assertSuccessful()
            ->assertJsonPath('slug', $slug);
    }

    /** @test */
    public function can_render_homepage()
    {
        $this->actingAs($this->user);
        $item = Page::factory()->create([
            'body' => '<p>This is homepage</p>',
            'slug' => 'home',
            'status_id' => $this->publishedStatus->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get($item->url)
            ->assertSuccessful()
            ->assertSeeText('This is homepage');
    }

    /** @test */
    public function can_render_page()
    {
        $this->actingAs($this->user);
        $item = Page::factory()->create([
            'body' => '<p>This is page</p>',
            'slug' => 'this-is-page',
            'status_id' => $this->publishedStatus->id,
        ]);

        $this->actingAs(User::factory()->create())
            ->get($item->url)
            ->assertSuccessful()
            ->assertSeeText('This is page');
    }

    /** @test */
    public function can_render_localized_page()
    {
        $this->actingAs($this->user);
        $item = Page::factory()->create([
            'body' => '<p>This is page</p>',
            'slug' => 'this-is-page',
            'status_id' => $this->publishedStatus->id,
            'ru' => [
                'body' => '<p>Это страница</p>',
                'slug' => 'this-is-page',
            ]
        ]);

        app()->setLocale('ru');

        $this->actingAs(User::factory()->create())
            ->get($item->url)
            ->assertSuccessful()
            ->assertSeeText('Это страница');
    }

    /** @test */
    public function can_not_render_not_existing_page()
    {
        $this->actingAs(User::factory()->create())
            ->get('/non-existing-page')
            ->assertStatus(404);
    }
    /** @test */
    public function can_not_create_with_wrong_slug()
    {
        $dummyData = $this->dummyData;
        if (!empty($this->dummyTranslatableData)) {
            $dummyData['translations'] = $this->dummyTranslatableData;
        }
        $dummyData['translations'][config('app.locale')]['slug'] = 'Wrong_Slug%';
        $this->actingAs($this->user)
            ->postJson($this->uri, $dummyData)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['translations.' . config('app.locale') . '.slug']);
    }
}
