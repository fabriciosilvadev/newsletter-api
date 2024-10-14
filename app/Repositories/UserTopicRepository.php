<?php

namespace App\Repositories;

use App\Contracts\UserTopicRepositoryContract;
use App\Models\Topic;
use App\Models\User;

class UserTopicRepository implements UserTopicRepositoryContract
{
    public function getTopicsByUserId(int $userId)
    {
        return User::findOrFail($userId)->topics()->get();
    }

    public function getUsersByTopicId(int $topicId)
    {
        return Topic::findOrFail($topicId)->users()->get();
    }

    public function addMany(int $userId, array $topicIds)
    {
        User::findOrFail($userId)->topics()->syncWithoutDetaching($topicIds);
    }

    public function removeMany(int $userId, array $topicIds)
    {
        User::findOrFail($userId)->topics()->detach($topicIds);
    }
}
