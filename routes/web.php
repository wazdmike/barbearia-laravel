<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Rotas da Aplicação - BarberVibe
|--------------------------------------------------------------------------
|
| Aqui são registadas todas as rotas da aplicação. Estas rotas são carregadas
| pelo RouteServaiceProvider e todas receberão o grupo de middleware "web".
|
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

    // CRUD e Painel de Agendamentos (Cliente)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // --------------------------------------------------------------------------
    // NOVAS ROTAS DE GESTÃO (Status e Eliminação Permanente)
    // --------------------------------------------------------------------------
    // Rota PATCH para atualizar o status do agendamento (Confirmado, Concluído, Cancelado)
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    
    // Rota DELETE para remover fisicamente um agendamento do banco de dados (Apenas Admin)
    Route::delete('/appointments/{appointment}/force', [AppointmentController::class, 'forceDelete'])->name('appointments.forceDelete');
    // --------------------------------------------------------------------------

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