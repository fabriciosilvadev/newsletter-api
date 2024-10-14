<?php

namespace App\Services;

use App\Contracts\UserTopicRepositoryContract;

class TopicSubscriptionService
{
    public function __construct(private UserTopicRepositoryContract $userTopicRepository) {}

    public function getAllSubscribedTopics(int $userId)
    {
        return $this->userTopicRepository->getTopicsByUserId($userId);
    }

    public function getAllSubscribedUsers(int $topicId)
    {
        return $this->userTopicRepository->getUsersByTopicId($topicId);
    }

    public function subscribe(int $userId, array $topicIds)
    {
        $this->userTopicRepository->addMany(userId: $userId, topicIds: $topicIds);
    }

    public function unsubscribe(int $userId, array $topicIds)
    {
        $this->userTopicRepository->removeMany(userId: $userId, topicIds: $topicIds);
    }
}
