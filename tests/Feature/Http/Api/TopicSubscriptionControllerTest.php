<?php

namespace Tests\Feature\Http\Api;

use App\Models\Topic;
use Tests\HasAuthenticatedUser;
use Tests\TestCase;

class TopicSubscriptionControllerTest extends TestCase
{
    use HasAuthenticatedUser;

    public function test_unauthorized_request(): void
    {
        $response = $this->get(route('topic-subscriptions.index'));

        $response->assertStatus(401);
    }

    public function test_authorized_request(): void
    {
        $this->setupUser();

        $response = $this->get(route('topic-subscriptions.index'));

        $response->assertStatus(200);
    }

    public function test_get_all_subscribed_topics(): void
    {
        $this->setupUser();
        $topics = Topic::factory(3)->create();
        $this->post(route('topic-subscriptions.subscribe'), [
            'topic_ids' => $topics->pluck('id')->toArray(),
        ]);

        $response = $this->get(route('topic-subscriptions.index'));

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }

    public function test_subscribe_to_topic(): void
    {
        $this->setupUser();
        $topics = Topic::factory(3)->create();

        $response = $this->post(route('topic-subscriptions.subscribe'), [
            'topic_ids' => $topics->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
    }

    public function test_fail_validation_when_subscribing_to_topic(): void
    {
        $this->setupUser();

        $response = $this->post(route('topic-subscriptions.subscribe'), [
            'topic_ids' => [9999],
        ]);

        $response->assertStatus(422);
    }

    public function test_unsubscribe_from_topic(): void
    {
        $this->setupUser();
        $topics = Topic::factory(3)->create();
        $this->user->topics()->attach($topics->pluck('id'));

        $response = $this->delete(route('topic-subscriptions.unsubscribe'), [
            'topic_ids' => $topics->pluck('id')->toArray(),
        ]);

        $response->assertStatus(200);
    }

    public function test_fail_validation_when_unsubscribing_to_topic(): void
    {
        $this->setupUser();

        $response = $this->post(route('topic-subscriptions.unsubscribe'), [
            'topic_ids' => [9999],
        ]);

        $response->assertStatus(422);
    }
}
