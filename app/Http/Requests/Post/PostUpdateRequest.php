<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'title' => trim($this->title),
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
             * Título da publicação
             *
             * @var string
             *
             * @example Este é o meu post
             */
            'title' => 'required',
            /**
             * Conteúdo da publicação
             *
             * @var string
             *
             * @example Este é o conteúdo do meu post
             */
            'content' => 'required',
            /**
             * ID do tópico ao qual a publicação será anexada
             *
             * @var int
             *
             * @example 1
             */
            'topic_id' => 'required|exists:topics,id',
        ];
    }
}
