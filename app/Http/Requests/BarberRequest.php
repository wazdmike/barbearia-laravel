<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BarberRequest extends FormRequest
{
    /**
     * Determina se o usuário tem permissão para esta requisição.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Regras de validação para criação e atualização de barbeiros.
     */
    public function rules(): array
    {
        $barberId = $this->route('barber')?->id ?? $this->route('barber');

        $rules = [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|max:255|unique:users,email' . ($barberId ? ',' . $barberId : ''),
        ];

        // Senha obrigatória na criação, opcional na edição
        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        return $rules;
    }

    /**
     * Mensagens de erro em português brasileiro.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do barbeiro é obrigatório.',
            'name.min' => 'O nome deve possuir no mínimo 3 caracteres.',
            'email.required' => 'O endereço de e-mail é obrigatório.',
            'email.email' => 'Insira um formato de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo utilizado por outro usuário no sistema.',
            'password.required' => 'A senha de acesso é obrigatória para o cadastro.',
            'password.min' => 'A senha deve possuir no mínimo 6 caracteres.',
        ];
    }
}
