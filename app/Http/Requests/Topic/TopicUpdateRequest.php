<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class TopicUpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Nome do tópico
             *
             * @example Tecnologia
             */
            'name' => 'required',
        ];
    }
}
