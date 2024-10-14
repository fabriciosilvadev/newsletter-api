<?php

namespace App\Http\Requests\Topic;

use Illuminate\Foundation\Http\FormRequest;

class TopicStoreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'name' => trim($this->name),
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
