<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\PostRepository;

class PostService
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getAll(int $page, int $perPage)
    {
        return $this->postRepository->getAll(page: $page, perPage: $perPage);
    }

    public function show($id)
    {
        return $this->postRepository->getById($id);
    }

    public function create(array $data, User $creator)
    {
        $data['creator_id'] = $creator->id;
        $post = $this->postRepository->create($data);
        $post->load('creator', 'topic');

        return $post;
    }

    public function update(int $id, array $data)
    {
        $this->postRepository->update(id: $id, data: $data);

        return $this->postRepository->getById($id);
    }

    public function delete($id)
    {
        return $this->postRepository->delete($id);
    }
}
