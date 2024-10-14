<?php

namespace App\Services;

use App\Contracts\TopicRepositoryContract;
use App\Models\User;

class TopicService
{
    public function __construct(private TopicRepositoryContract $topicRepository) {}

    public function getAll(int $page, int $perPage)
    {
        return $this->topicRepository->getAll(page: $page, perPage: $perPage);
    }

    public function show(int $id)
    {
        return $this->topicRepository->getById($id);
    }

    public function create(array $data, User $creator)
    {
        $data['creator_id'] = $creator->id;
        $topic = $this->topicRepository->create($data);
        $topic->load('creator');
        $topic->loadCount('posts');

        return $topic;
    }

    public function update(int $id, array $data)
    {
        $this->topicRepository->update(id: $id, data: $data);

        return $this->topicRepository->getById($id);
    }

    public function delete(int $id)
    {
        return $this->topicRepository->delete($id);
    }
}
