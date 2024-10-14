<?php

namespace App\Repositories;

use App\Contracts\PostRepositoryContract;
use App\Models\Post;

class PostRepository implements PostRepositoryContract
{
    public function getAll($page = 1, $perPage = 20)
    {
        return Post::with('creator', 'topic')->simplePaginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id)
    {
        return Post::with('creator', 'topic')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update(int $id, array $data)
    {
        return Post::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Post::destroy($id);
    }
}
