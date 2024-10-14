<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function __construct(private PostService $postService) {}

    /**
     * Listar publicações
     *
     * Obtém uma lista de todas as publicações cadastradas.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Post::class);

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
        $posts = $this->postService->getAll($page, $perPage);

        return new PostCollection($posts);
    }

    /**
     * Criar publicação
     *
     * Cria uma nova publicação.
     */
    public function store(PostStoreRequest $request)
    {
        $this->authorize('create', Post::class);

        $user = auth()->user();
        $data = $request->validated();
        $post = $this->postService->create($data, $user);

        return $this->success(data: new PostResource($post), statusCode: 201);
    }

    /**
     * Exibir publicação
     *
     * Exibe os detalhes de uma publicação.
     *
     * @param  Post  $post  ID do post a ser exibido
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);

        $post->load('creator', 'topic');

        return $this->success(data: new PostResource($post));
    }

    /**
     * Atualizar publicação
     *
     * Atualiza os dados de uma publicação.
     *
     * @param  Post  $post  ID do post a ser atualizado
     */
    public function update(Post $post, PostUpdateRequest $request)
    {
        $this->authorize('update', $post);

        $data = $request->validated();
        $updated = $this->postService->update($post->id, $data);

        return $this->success(data: new PostResource($updated));
    }

    /**
     * Excluir publicação
     *
     * Remove uma publicação específica do sistema.
     *
     * @param  Post  $post  ID do post a ser deletado
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $this->postService->delete($post->id);

        return $this->success(data: null, message: 'Publicação excluída com sucesso.');
    }
}
