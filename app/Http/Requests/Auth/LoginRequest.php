<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * E-mail do usuário
             *
             * @example admin@admin.com
             */
            'email' => 'required|email',
            /**
             * Senha do usuário
             *
             * @example admin
             */
            'password' => 'required',
        ];
    }
}
