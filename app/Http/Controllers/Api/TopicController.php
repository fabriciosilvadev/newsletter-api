<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Topic\TopicStoreRequest;
use App\Http\Requests\Topic\TopicUpdateRequest;
use App\Http\Resources\TopicCollection;
use App\Http\Resources\TopicResource;
use App\Models\Topic;
use App\Services\TopicService;
use Illuminate\Http\Request;

class TopicController extends ApiController
{
    public function __construct(private TopicService $topicService) {}

    /**
     * Listar tópicos
     *
     * Obtém uma lista de todos os tópicos cadastrados.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Topic::class);

        /**
         * Página a ser exibida
         *
         * @example 1
         *
         * @default 1
         */
        $page = $request->integer('page', 1);
        /**
         * Quantidade de itens por página
         *
         * @example 30
         *
         * @default 20
         */
        $perPage = $request->integer('per_page', 20);
        $topics = $this->topicService->getAll($page, $perPage);

        return new TopicCollection($topics);
    }

    /**
     * Criar tópico
     *
     * Cria um novo tópico.
     */
    public function store(TopicStoreRequest $request)
    {
        $this->authorize('create', Topic::class);

        $user = auth()->user();
        $data = $request->validated();
        $topic = $this->topicService->create($data, $user);

        return $this->success(data: new TopicResource($topic), statusCode: 201);
    }

    /**
     * Exibir tópico
     *
     * Obtém um tópico específico.
     *
     * @param  Topic  $topic  ID do tópico
     */
    public function show(Topic $topic)
    {
        $this->authorize('view', $topic);

        $topic->load('creator');

        return $this->success(data: new TopicResource($topic));
    }

    /**
     * Atualizar tópico
     *
     * Atualiza um tópico existente.
     *
     * @param  Topic  $topic  ID do tópico
     */
    public function update(Topic $topic, TopicUpdateRequest $request)
    {
        $this->authorize('update', $topic);

        $data = $request->validated();
        $updated = $this->topicService->update($topic->id, $data);

        return $this->success(data: new TopicResource($updated));
    }

    /**
     * Excluir tópico
     *
     * Remove um tópico existente.
     *
     * @param  Topic  $topic  ID do tópico
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic);

        $this->topicService->delete($topic->id);

        return $this->success(data: null, message: 'Tópico excluído com sucesso.');
    }
}
