<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Processa a autenticação do utilizador.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Insira um e-mail válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirecionamento dinâmico baseado no papel (role)
            $user = Auth::user();
            if ($user->role === 'admin' || $user->role === 'barber') {
                return redirect()->intended(route('management'));
            }

            return redirect()->intended(route('appointments.index'));
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registos.',
        ])->onlyInput('email');
    }

    /**
     * Exibe o formulário de registo para novos clientes.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Processa o registo de um novo cliente.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Insira um formato de e-mail válido.',
            'email.unique' => 'Este e-mail já está registado no nosso sistema.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve conter no mínimo 6 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ]);

        // Cria o utilizador com o papel 'client' de forma automática
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'client', // Sempre cliente no autocadastro
        ]);

        // Realiza o login automático do novo cliente
        Auth::login($user);

        return redirect()->route('appointments.index')
            ->with('success', 'A sua conta foi criada com sucesso! Seja bem-vindo ao BarberVibe.');
    }

    /**
     * Finaliza a sessão do utilizador logado.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
