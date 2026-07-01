<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Garante que um cliente comum é redirecionado para a página de agendamentos após o login.
     */
    public function test_client_user_is_redirected_to_appointments_after_login(): void
    {
        // Criamos o utilizador garantindo que a senha está devidamente encriptada
        $user = User::factory()->create([
            'email' => 'client@example.com',
            'password' => Hash::make('password123'),
            'role' => 'client',
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Asserção correta na resposta
        $response->assertRedirect(route('appointments.index'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Garante que administradores e barbeiros são redirecionados para o painel de gestão após o login.
     */
    public function test_admin_and_barber_users_are_redirected_to_management_after_login(): void
    {
        foreach (['admin', 'barber'] as $role) {
            $user = User::factory()->create([
                'email' => "{$role}@example.com",
                'password' => Hash::make('password123'),
                'role' => $role,
            ]);

            $response = $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'password123',
            ]);

            // Capturamos a resposta para fazer a asserção de redirecionamento correta
            $response->assertRedirect(route('management'));
            $this->assertAuthenticatedAs($user);

            // Fazemos o logout limpo para o próximo ciclo do loop
            Auth::logout();
        }
    }
}
