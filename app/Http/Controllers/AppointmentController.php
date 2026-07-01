<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Exibe a listagem de agendamentos do cliente e o formulário de marcação.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Puxa apenas os agendamentos do cliente autenticado
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['barber', 'service'])
            ->latest()
            ->paginate(10);

        // Puxa todos os serviços disponíveis para o formulário
        $services = Service::all();

        // Puxa todos os utilizadores que são barbeiros ativos
        $barbers = User::where('role', 'barber')->get();

        return view('appointments.index', compact('appointments', 'services', 'barbers'));
    }

    /**
     * Regista um novo agendamento de forma segura na base de dados.
     */
    public function store(AppointmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $dateTime = Carbon::parse($validated['date_time']);

        // 1. Validação de Regra de Negócio: Não permitir agendamentos no passado
        if ($dateTime->isPast()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Não é possível realizar um agendamento numa data ou hora passada.');
        }

        // 2. Validação de Horário de Funcionamento (09:00 às 19:00)
        $hour = $dateTime->hour;
        if ($hour < 9 || $hour >= 19) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A barbearia apenas funciona entre as 09:00 e as 19:00.');
        }

        // 3. Validação de Conflito de Agenda (Evita duplicados para o mesmo barbeiro no mesmo horário)
        $conflict = Appointment::where('barber_id', $validated['barber_id'])
            ->where('date_time', $validated['date_time'])
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'O barbeiro selecionado já possui um agendamento marcado para este horário.');
        }

        // Cria o agendamento associando o utilizador logado como cliente
        Appointment::create([
            'client_id' => Auth::id(),
            'barber_id' => $validated['barber_id'],
            'service_id' => $validated['service_id'],
            'date_time' => $dateTime,
            'status' => 'pending'
        ]);

        return redirect()->route('appointments.index')
            ->with('success', 'Marcação realizada com sucesso! Aguarde a confirmação.');
    }

    /**
     * Cancela um agendamento existente de forma segura.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        // Garante que o cliente só pode cancelar o seu próprio agendamento
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Em vez de apagar fisicamente, alteramos o estado para cancelado
        $appointment->update(['status' => 'canceled']);

        return redirect()->route('appointments.index')
            ->with('success', 'Marcação cancelada com sucesso.');
    }
}