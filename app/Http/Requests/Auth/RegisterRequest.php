<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Nome do usuário
             *
             * @example João da Silva
             */
            'name' => 'required|string',
            /**
             * E-mail do usuário
             *
             * @example joao@email.com
             */
            'email' => 'required|email|unique:users,email',
            /**
             * Senha do usuário
             *
             * @example 123456
             */
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
