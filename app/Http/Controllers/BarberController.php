<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\BarberRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BarberController extends Controller
{
    /**
     * Garante que apenas administradores acessem o painel de barbeiros.
     */
    private function checkAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem gerenciar a equipe de barbeiros.');
        }
    }

    /**
     * Exibe a listagem de todos os barbeiros registrados no sistema.
     */
    public function index(): View
    {
        $this->checkAdmin();

        // Lista apenas os usuários que possuem a função de barbeiro
        $barbers = User::where('role', 'barber')->latest()->paginate(10);

        return view('barbers.index', compact('barbers'));
    }

    /**
     * Registra um novo barbeiro profissional no sistema.
     */
    public function store(BarberRequest $request): RedirectResponse
    {
        $this->checkAdmin();

        $validated = $request->validated();

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'barber', // Função fixa de barbeiro
        ]);

        return redirect()->route('barbers.index')
            ->with('success', 'Barbeiro profissional cadastrado com sucesso!');
    }

    /**
     * Atualiza as informações básicas de um barbeiro.
     */
    public function update(BarberRequest $request, User $barber): RedirectResponse
    {
        $this->checkAdmin();

        // Impede que altere o perfil caso não seja barbeiro por segurança
        if ($barber->role !== 'barber') {
            abort(400, 'Ação inválida.');
        }

        $validated = $request->validated();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Se uma nova senha foi fornecida, atualiza com hash seguro
        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $barber->update($data);

        return redirect()->route('barbers.index')
            ->with('success', 'Cadastro do barbeiro atualizado com sucesso!');
    }

    /**
     * Remove um barbeiro profissional da equipe ativa.
     */
    public function destroy(User $barber): RedirectResponse
    {
        $this->checkAdmin();

        if ($barber->role !== 'barber') {
            abort(400, 'Ação inválida.');
        }

        // Regra de Negócio: Impede remoção caso haja compromissos ativos pendentes/confirmados
        $hasActiveAppointments = $barber->appointments()
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($hasActiveAppointments) {
            return redirect()->route('barbers.index')
                ->with('error', 'Não é possível remover este barbeiro pois ele possui agendamentos pendentes ou confirmados ativos.');
        }

        $barber->delete();

        return redirect()->route('barbers.index')
            ->with('success', 'Barbeiro removido da equipe com sucesso.');
    }
}
