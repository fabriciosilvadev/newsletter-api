<?php

namespace Tests\Unit\Services;

use App\Models\Topic;
use App\Models\User;
use App\Services\TopicService;
use Tests\TestCase;

class TopicServiceTest extends TestCase
{
    protected function makeService($connection)
    {
        return new TopicService($connection);
    }

    public function test_get_all_topics()
    {
        Topic::factory()->count(6)->create();
        $service = $this->app->make(TopicService::class);
        $page = 1;
        $perPage = 10;

        $topics = $service->getAll($page, $perPage);

        $this->assertCount(6, $topics);
    }

    public function test_show_topic_by_id()
    {
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicService::class);

        $topic = $service->show($topic->id);

        $this->assertNotNull($topic);
    }

    public function test_create_topic()
    {
        $user = User::factory()->create();
        $service = $this->app->make(TopicService::class);
        $data = ['name' => fake()->word()];

        $topic = $service->create($data, $user);

        $this->assertNotNull($topic);
        $this->assertEquals($data['name'], $topic->name);
    }

    public function test_update_topic()
    {
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicService::class);
        $data = ['name' => fake()->word()];

        $result = $service->update($topic->id, $data);

        $this->assertNotNull($result);
        $this->assertEquals($result['id'], $topic->id);
        $this->assertEquals($result['name'], $data['name']);
    }

    public function test_delete_topic()
    {
        $topic = Topic::factory()->create();
        $service = $this->app->make(TopicService::class);

        $result = $service->delete($topic->id);

        $this->assertNotNull($result);
        $this->assertEquals($result, 1);
    }
}
