<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /** @test */
    public function update_profile_info()
    {
        $this->actingAs($user = User::factory()->create())
            ->patchJson('/api/settings/profile', [
                'name' => 'Test User',
                'email' => 'test@test.app',
            ])
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'name', 'email']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Test User',
            'email' => 'test@test.app',
        ]);
    }

    /** @test */
    public function update_password()
    {
        $this->actingAs($user = User::factory()->create())
            ->patchJson('/api/settings/password', [
                'password' => 'updated00',
                'password_confirmation' => 'updated00',
            ])
            ->assertSuccessful();

        $this->assertTrue(Hash::check('updated00', $user->password));
    }
}
