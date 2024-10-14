<?php

namespace App\Contracts;

interface UserTopicRepositoryContract
{
    public function getTopicsByUserId(int $userId);

    public function getUsersByTopicId(int $topicId);

    public function addMany(int $userId, array $topicIds);

    public function removeMany(int $userId, array $topicIds);
}
