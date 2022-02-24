<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /** @test */
    public function authenticate()
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertSuccessful()
            ->assertJsonStructure(['token', 'expires_in'])
            ->assertJson(['token_type' => 'bearer']);
    }

    /** @test */
    public function can_not_authenticate_not_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function fetch_the_current_user()
    {
        $this->actingAs(User::factory()->create())
            ->getJson('/api/user')
            ->assertSuccessful()
            ->assertJsonStructure(['id', 'name', 'email']);
    }

    /** @test */
    public function log_out()
    {
        $token = $this->postJson('/api/login', [
            'email' => User::factory()->create()->email,
            'password' => 'password',
        ])->json()['token'];

        $this->postJson("/api/logout?token=$token")
            ->assertSuccessful();

        $this->getJson("/api/user?token=$token")
            ->assertStatus(401);
    }

    /** @test */
    public function can_throttle()
    {
        for ($i = 0; $i <= 100; $i++) {
            $this->postJson('/api/login', [
                'email' => 'test@test.app',
                'password' => 'testmenow',
            ]);
        }
        $this->postJson('/api/login', [
            'email' => 'test@test.app',
            'password' => 'testmenow',
        ])
            ->assertStatus(422)
            ->assertSeeText('Too many login attempts');
    }
}
