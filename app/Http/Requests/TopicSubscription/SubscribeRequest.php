<?php

namespace App\Http\Requests\TopicSubscription;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            /**
             * Lista com o ID dos tópicos
             *
             * @example [1, 2, 3]
             */
            'topic_ids' => 'required|array|min:1',
            'topic_ids.*' => 'required|integer|exists:topics,id',
        ];
    }
}
