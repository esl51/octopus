<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /** @test */
    public function can_send_reset_link()
    {
        $user = User::factory()->create();
        $this->postJson('/api/password/email', [
            'email' => $user->email,
        ])
            ->assertSuccessful()
            ->assertJsonFragment(['status' => trans('passwords.sent')]);
    }
}
