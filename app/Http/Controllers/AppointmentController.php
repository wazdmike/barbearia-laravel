<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Http\Request;
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
        $service = Service::findOrFail($validated['service_id']);
        $endDateTime = $dateTime->copy()->addMinutes($service->duration_minutes);

        if ($dateTime->isPast()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Não é possível realizar um agendamento numa data ou hora passada.');
        }

        $hour = $dateTime->hour;
        if ($hour < 9 || $hour >= 19) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A barbearia apenas funciona entre as 09:00 e as 19:00.');
        }

        $existingAppointments = Appointment::where('barber_id', $validated['barber_id'])
            ->where('status', '!=', 'canceled')
            ->whereDate('date_time', $dateTime->toDateString())
            ->with('service')
            ->get();

        foreach ($existingAppointments as $existingAppointment) {
            $existingStart = $existingAppointment->date_time;
            $existingEnd = $existingStart->copy()->addMinutes($existingAppointment->service->duration_minutes);

            if ($dateTime->lt($existingEnd) && $endDateTime->gt($existingStart)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'O barbeiro selecionado já possui um agendamento marcado para este horário.');
            }
        }

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
     * Cancela um agendamento existente (Ação do Cliente).
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        // Garante que o cliente só pode cancelar o seu próprio agendamento
        if ($appointment->client_id !== Auth::id()) {
            abort(403, 'Ação não autorizada.');
        }

        // Em vez de apagar fisicamente, alteramos o estado para cancelado para fins de histórico
        $appointment->update(['status' => 'canceled']);

        return redirect()->route('appointments.index')
            ->with('success', 'Marcação cancelada com sucesso.');
    }

    /**
     * Atualiza o status do agendamento (Ação do Administrador ou Barbeiro).
     */
    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'barber') {
            abort(403, 'Acesso não autorizado.');
        }

        // Se for um barbeiro comum, ele só pode alterar a agenda dele mesmo
        if ($user->role === 'barber' && $appointment->barber_id !== $user->id) {
            abort(403, 'Você só pode gerenciar os seus próprios atendimentos.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled'
        ]);

        $appointment->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'O status do agendamento foi alterado para ' . $this->translateStatus($validated['status']) . '!');
    }

    /**
     * Exclui definitivamente um agendamento do banco de dados (Ação exclusiva do Administrador).
     */
    public function forceDelete(Appointment $appointment): RedirectResponse
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Apenas administradores podem remover permanentemente registros do sistema.');
        }

        $appointment->delete();

        return redirect()->back()->with('success', 'Agendamento excluído do sistema permanentemente.');
    }

    /**
     * Método auxiliar para tradução de termos de status na mensagem de retorno.
     */
    private function translateStatus(string $status): string
    {
        return [
            'pending' => 'Pendente',
            'confirmed' => 'Confirmado',
            'completed' => 'Concluído',
            'canceled' => 'Cancelado'
        ][$status] ?? $status;
    }
}
