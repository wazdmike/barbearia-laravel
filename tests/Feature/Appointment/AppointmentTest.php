<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;

class AppointmentTest extends TestCase
{
    // A trait RefreshDatabase garante que a base de dados de testes é migrada 
    // e limpa a cada execução, evitando dados residuais entre os testes.
    use RefreshDatabase;

    /**
     * Testa se um utilizador não autenticado (visitante) é redirecionado para o login.
     */
    public function test_guest_cannot_access_appointments_page(): void
    {
        $response = $this->get(route('appointments.index'));

        // Deve redirecionar para a página de login
        $response->assertRedirect(route('login'));
    }

    /**
     * Testa se um cliente autenticado consegue visualizar o seu painel de agendamentos.
     */
    public function test_authenticated_client_can_access_appointments_page(): void
    {
        // 1. Cria um utilizador de teste com papel de cliente
        $client = User::factory()->create([
            'role' => 'client'
        ]);

        // 2. Autentica o utilizador e acede à rota
        $response = $this->actingAs($client)->get(route('appointments.index'));

        // 3. Verifica se a página carregou com sucesso (HTTP 200)
        $response->assertStatus(200);
        $response->assertViewIs('appointments.index');
    }

    /**
     * Testa se um cliente consegue realizar um agendamento com sucesso seguindo as regras.
     */
    public function test_client_can_create_appointment_with_valid_data(): void
    {
        // 1. Cria o cenário com utilizadores e serviço
        $client = User::factory()->create(['role' => 'client']);
        $barber = User::factory()->create(['role' => 'barber']);
        $service = Service::create([
            'name' => 'Corte Simples',
            'price' => 30.00,
            'duration_minutes' => 30
        ]);

        // Define um horário válido no futuro dentro do expediente (ex: amanhã às 14:00)
        $validDateTime = Carbon::tomorrow()->setHour(14)->setMinute(0)->toDateTimeString();

        // 2. Envia a requisição POST para salvar o agendamento
        $response = $this->actingAs($client)->post(route('appointments.store'), [
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $validDateTime,
        ]);

        // 3. Verifica se foi redirecionado com mensagem de sucesso
        $response->assertRedirect(route('appointments.index'));
        $response->assertSessionHas('success', 'Marcação realizada com sucesso! Aguarde a confirmação.');

        // 4. Confirma se o registo foi gravado corretamente na base de dados
        $this->assertDatabaseHas('appointments', [
            'client_id' => $client->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $validDateTime,
            'status' => 'pending'
        ]);
    }

    /**
     * Testa a regra de negócio: Não permitir agendamentos no passado.
     */
    public function test_client_cannot_create_appointment_in_the_past(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $barber = User::factory()->create(['role' => 'barber']);
        $service = Service::create([
            'name' => 'Barba',
            'price' => 20.00,
            'duration_minutes' => 20
        ]);

        // Define um horário no passado (ex: ontem às 10:00)
        $pastDateTime = Carbon::yesterday()->setHour(10)->setMinute(0)->toDateTimeString();

        $response = $this->actingAs($client)->post(route('appointments.store'), [
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $pastDateTime,
        ]);

        // Deve redirecionar de volta com mensagem de erro na sessão
        $response->assertSessionHas('error', 'Não é possível realizar um agendamento numa data ou hora passada.');

        // Garante que o registo NÃO foi criado no banco
        $this->assertDatabaseMissing('appointments', [
            'date_time' => $pastDateTime
        ]);
    }

    /**
     * Testa a regra de negócio: Não permitir agendamento fora do horário de funcionamento (09:00 às 19:00).
     */
    public function test_client_cannot_create_appointment_outside_business_hours(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $barber = User::factory()->create(['role' => 'barber']);
        $service = Service::create([
            'name' => 'Corte',
            'price' => 30.00,
            'duration_minutes' => 30
        ]);

        // Define um horário fora do expediente (ex: amanhã às 22:00)
        $invalidDateTime = Carbon::tomorrow()->setHour(22)->setMinute(0)->toDateTimeString();

        $response = $this->actingAs($client)->post(route('appointments.store'), [
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $invalidDateTime,
        ]);

        $response->assertSessionHas('error', 'A barbearia apenas funciona entre as 09:00 e as 19:00.');
        $this->assertDatabaseMissing('appointments', [
            'date_time' => $invalidDateTime
        ]);
    }

    /**
     * Testa a regra de negócio: Impedir agendamentos duplicados (conflitos de horário) para o mesmo barbeiro.
     */
    public function test_client_cannot_create_conflicting_appointment(): void
    {
        $client1 = User::factory()->create(['role' => 'client']);
        $client2 = User::factory()->create(['role' => 'client']);
        $barber = User::factory()->create(['role' => 'barber']);
        $service = Service::create([
            'name' => 'Corte',
            'price' => 30.00,
            'duration_minutes' => 30
        ]);

        // Horário compartilhado
        $sharedDateTime = Carbon::tomorrow()->setHour(15)->setMinute(0)->toDateTimeString();

        // 1. Cria o primeiro agendamento ativo
        Appointment::create([
            'client_id' => $client1->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $sharedDateTime,
            'status' => 'confirmed'
        ]);

        // 2. Tenta criar um segundo agendamento com o mesmo barbeiro no mesmo horário
        $response = $this->actingAs($client2)->post(route('appointments.store'), [
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'date_time' => $sharedDateTime,
        ]);

        // 3. Deve dar erro de conflito
        $response->assertSessionHas('error', 'O barbeiro selecionado já possui um agendamento marcado para este horário.');

        // Garante que o segundo registo não foi inserido associado ao cliente 2
        $this->assertDatabaseMissing('appointments', [
            'client_id' => $client2->id,
            'date_time' => $sharedDateTime
        ]);
    }
}
