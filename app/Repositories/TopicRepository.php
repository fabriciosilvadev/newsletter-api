<?php

namespace App\Repositories;

use App\Contracts\TopicRepositoryContract;
use App\Models\Topic;

class TopicRepository implements TopicRepositoryContract
{
    public function getAll($page = 1, $perPage = 20)
    {
        return Topic::with('creator')
            ->withCount('posts')
            ->simplePaginate($perPage, ['*'], 'page', $page);
    }

    public function getById($id)
    {
        return Topic::with('creator')->withCount('posts')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Topic::create($data);
    }

    public function update(int $id, array $data)
    {
        return Topic::whereId($id)->update($data);
    }

    public function delete($id)
    {
        return Topic::destroy($id);
    }
}
