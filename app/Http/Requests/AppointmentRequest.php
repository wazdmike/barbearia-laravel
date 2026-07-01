<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    /**
     * Determina se o utilizador tem autorização para efetuar esta requisição.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'client';
    }

    /**
     * Obtém as regras de validação que se aplicam à requisição.
     */
    public function rules(): array
    {
        return [
            'barber_id' => ['required', Rule::exists('users', 'id')->where('role', 'barber')],
            'service_id' => 'required|exists:services,id',
            'date_time' => 'required|date',
        ];
    }

    /**
     * Personaliza as mensagens de erro em português.
     */
    public function messages(): array
    {
        return [
            'barber_id.required' => 'A seleção de um barbeiro é obrigatória.',
            'barber_id.exists' => 'O barbeiro selecionado não é válido.',
            'service_id.required' => 'A seleção de um serviço é obrigatória.',
            'service_id.exists' => 'O serviço selecionado não é válido.',
            'date_time.required' => 'A data e hora da marcação são obrigatórias.',
            'date_time.date' => 'Insira uma data e hora válidas.',
        ];
    }
}
