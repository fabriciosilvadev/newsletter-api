<?php

namespace Tests\Unit\Services;

use App\Models\Topic;
use App\Models\User;
use App\Services\TopicSubscriptionService;
use Tests\TestCase;

class TopicSubscriptionServiceTest extends TestCase
{
    protected function makeService($connection)
    {
        return new TopicSubscriptionService($connection);
    }

    public function test_get_all_subscribed_topics()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicSubscriptionService::class);
        $service->subscribe($user->id, [$topic->id]);

        $topics = $service->getAllSubscribedTopics($user->id);

        $this->assertCount(1, $topics);
    }

    public function test_get_all_subscribed_users()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicSubscriptionService::class);
        $service->subscribe($user->id, [$topic->id]);

        $users = $service->getAllSubscribedUsers($topic->id);

        $this->assertCount(1, $users);
    }

    public function test_subscribe_topic()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicSubscriptionService::class);

        $result = $service->subscribe($user->id, [$topic->id]);

        $this->assertNull($result);
        $this->assertDatabaseHas('users_topics', ['user_id' => $user->id, 'topic_id' => $topic->id]);
    }

    public function test_unsubscribe_topic()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicSubscriptionService::class);

        $result = $service->unsubscribe($user->id, [$topic->id]);

        $this->assertNull($result);
        $this->assertDatabaseMissing('users_topics', ['user_id' => $user->id, 'topic_id' => $topic->id]);
    }
}
