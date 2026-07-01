<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BarberController;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Rotas da Aplicação - BarberVibe
|--------------------------------------------------------------------------
*/

// 1. Rota da Página Inicial (Landing Page)
Route::get('/', function () {
    $services = \App\Models\Service::all();
    return view('welcome', compact('services'));
})->name('home');

// 2. Rotas de Autenticação e Registo (Apenas para utilizadores NÃO autenticados)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Rota de Logout (Apenas para utilizadores autenticados)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 4. Rotas Protegidas (Requer login ativo)
Route::middleware(['auth'])->group(function () {

    // CRUD e Painel de Agendamentos (Cliente)
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Rotas de Gestão de Agendamentos (Status e Eliminação Permanente)
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
    Route::delete('/appointments/{appointment}/force', [AppointmentController::class, 'forceDelete'])->name('appointments.forceDelete');

    // Painel de Gestão Principal (Administração e escala dos Barbeiros)
    Route::get('/management', function (Request $request) {
        $user = $request->user();

        if ($user && $user->role === 'client') {
            return redirect()->route('appointments.index');
        }

        $query = Appointment::with(['client', 'barber', 'service']);

        if ($user && $user->role === 'barber') {
            $query->where('barber_id', $user->id);
        }

        $appointments = $query->latest()->paginate(10);

        return view('management.index', compact('appointments'));
    })->name('management');

    // CRUD de Serviços (Exclusivo Admin)
    Route::resource('services', ServiceController::class);

    // CRUD de Barbeiros (Exclusivo Admin)
    Route::resource('barbers', BarberController::class)->except(['create', 'show', 'edit']);
});
