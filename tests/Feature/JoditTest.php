<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionTablesSeeder;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class JoditTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionTablesSeeder::class);
        $this->user = User::factory()->afterCreating(function ($model) {
            $model->assignRole('root');
        })->create();
    }

    /** @test */
    public function can_browse()
    {
        $this->actingAs($this->user)
            ->post('/api/jodit/browse', [
                'action' => 'folders',
                'path' => '',
                'source' => 'default',
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['data' => ['sources' => ['default' => ['baseurl', 'files', 'folders', 'path']]]]);
    }

    /** @test */
    public function can_upload_image()
    {
        $file = UploadedFile::fake()->image('jodit_test_upload.jpg', 1024, 768);
        $this->actingAs($this->user)
            ->post('/api/jodit/upload', [
                'path' => '',
                'source' => 'default',
                'files' => [$file],
            ])
            ->assertSuccessful();
        $this->storage->assertExists('public/media/jodit-test-upload.jpg');
    }
}
