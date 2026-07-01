<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Rotas da Aplicação - BarberVibe
|--------------------------------------------------------------------------
*/

// 1. Rota da Página Inicial (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Rotas de Autenticação (Apenas para utilizadores NÃO autenticados)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// 3. Rota de Logout (Apenas para utilizadores autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 4. Rotas Protegidas (Requer login ativo)
Route::middleware(['auth'])->group(function () {

    // Painel do Cliente (Visualização e agendamentos)
    Route::get('/appointments', function (Request $request) {
        $user = $request->user();

        // Se um administrador ou barbeiro tentar aceder aqui, redireciona de forma amigável
        if ($user && ($user->role === 'admin' || $user->role === 'barber')) {
            return redirect()->route('management');
        }

        // Puxa apenas os agendamentos do cliente logado no MySQL
        $appointments = Appointment::where('client_id', $user->id)
            ->with(['barber', 'service'])
            ->latest()
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    })->name('appointments.index');

    // Painel de Gestão (Administração e escala dos Barbeiros)
    Route::get('/management', function (Request $request) {
        $user = $request->user();

        // Se for um cliente comum, redireciona para a agenda pessoal dele
        if ($user && $user->role === 'client') {
            return redirect()->route('appointments.index');
        }

        $query = Appointment::with(['client', 'barber', 'service']);

        // Se for barbeiro, ele só consegue visualizar os agendamentos dele próprio
        if ($user && $user->role === 'barber') {
            $query->where('barber_id', $user->id);
        }

        $appointments = $query->latest()->paginate(10);

        return view('management.index', compact('appointments'));
    })->name('management');

    // CRUD de Serviços (Acesso controlado integrado no Controller)
    Route::resource('services', ServiceController::class);
});
