<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_user_is_redirected_to_appointments_after_login(): void
    {
        $user = User::factory()->create([
            'email' => 'client@example.com',
            'password' => 'password123',
            'role' => 'client',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('appointments.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_and_barber_users_are_redirected_to_management_after_login(): void
    {
        foreach (['admin', 'barber'] as $role) {
            $user = User::factory()->create([
                'email' => "$role@example.com",
                'password' => 'password123',
                'role' => $role,
            ]);

            $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'password123',
            ]);

            $this->assertRedirect(route('management'));
            $this->assertAuthenticatedAs($user);
            $this->app['auth']->logout();
        }
    }
}
