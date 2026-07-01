<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Verifica se o utilizador logado é administrador.
     *
     * @return void
     */
    protected function ensureAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado. Apenas administradores podem acessar esta área.');
        }
    }
}
