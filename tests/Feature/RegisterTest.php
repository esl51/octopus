<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_register()
    {
        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret00',
            'password_confirmation' => 'secret00',
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['status' => trans('verification.sent')]);
    }

    /** @test */
    public function can_not_register_with_existing_email()
    {
        User::factory()->create(['email' => 'test@test.app']);

        $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@test.app',
            'password' => 'secret00',
            'password_confirmation' => 'secret00',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
