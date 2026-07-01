<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends FormRequest
{
    /**
     * Determina se o utilizador tem autorização para efetuar esta requisição.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Obtém as regras de validação aplicadas à requisição.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|min:3',
            'price' => 'required|numeric|min:0.01|max:999.99',
            'duration_minutes' => 'required|integer|min:5|max:240',
        ];
    }

    /**
     * Personaliza as mensagens de erro de validação em português.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do serviço é obrigatório.',
            'name.min' => 'O nome do serviço deve ter pelo menos 3 caracteres.',
            'name.max' => 'O nome do serviço não pode exceder 100 caracteres.',
            'price.required' => 'O preço do serviço é obrigatório.',
            'price.numeric' => 'O preço deve ser um valor numérico válido.',
            'price.min' => 'O preço do serviço deve ser maior que zero.',
            'duration_minutes.required' => 'A duração estimada em minutos é obrigatória.',
            'duration_minutes.integer' => 'A duração deve ser expressa em minutos inteiros.',
            'duration_minutes.min' => 'A duração do serviço deve ser de no mínimo 5 minutos.',
            'duration_minutes.max' => 'A duração do serviço não pode exceder 4 horas.',
        ];
    }
}
