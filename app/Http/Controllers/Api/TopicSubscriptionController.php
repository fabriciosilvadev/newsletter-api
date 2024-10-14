<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TopicSubscription\SubscribeRequest;
use App\Http\Requests\TopicSubscription\UnsubscribeRequest;
use App\Http\Resources\TopicSubscriptionCollection;
use App\Services\TopicSubscriptionService;

class TopicSubscriptionController extends ApiController
{
    public function __construct(private TopicSubscriptionService $topicSubscriptionService) {}

    /**
     * Listar tópicos inscritos
     *
     * Obtém uma lista de todos os tópicos inscritos pelo usuário autenticado.
     */
    public function index()
    {
        $user = auth()->user();
        $topicSubscriptions = $this->topicSubscriptionService->getAllSubscribedTopics($user->id);

        return $this->success(new TopicSubscriptionCollection($topicSubscriptions));
    }

    /**
     * Inscrever-se em tópicos
     *
     * Inscreve o usuário autenticado em tópicos.
     */
    public function subscribe(SubscribeRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $this->topicSubscriptionService->subscribe($user->id, $validated['topic_ids']);

        return $this->success(data: null, message: 'Inscrições realizadas com sucesso.');
    }

    /**
     * Cancelar inscrição em tópicos
     *
     * Cancela a inscrição do usuário autenticado em tópicos.
     */
    public function unsubscribe(UnsubscribeRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $this->topicSubscriptionService->unsubscribe($user->id, $validated['topic_ids']);

        return $this->success(data: null, message: 'Inscrições canceladas com sucesso.');
    }
}
