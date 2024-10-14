<?php

namespace Tests\Unit\Repositories;

use App\Models\Topic;
use App\Repositories\TopicRepository;
use Tests\TestCase;

class TopicRepositoryTest extends TestCase
{
    protected function makeRepository($connection)
    {
        return new TopicRepository($connection);
    }

    public function test_get_all_topics()
    {
        Topic::factory()->count(6)->create();
        $repository = $this->app->make(TopicRepository::class);
        $page = 1;
        $perPage = 10;

        $topics = $repository->getAll($page, $perPage);

        $this->assertCount(6, $topics);
    }

    public function test_get_topic_by_id()
    {
        $topic = Topic::factory()->create();
        $repository = $this->app->make(TopicRepository::class);

        $topic = $repository->getById($topic->id);

        $this->assertNotNull($topic);
    }

    public function test_create_topic()
    {
        $repository = $this->app->make(TopicRepository::class);
        $data = ['name' => fake()->word()];

        $topic = $repository->create($data);

        $this->assertNotNull($topic);
        $this->assertEquals($data['name'], $topic->name);
    }

    public function test_update_topic()
    {
        $topic = Topic::factory()->create();
        $repository = $this->app->make(TopicRepository::class);
        $data = ['name' => fake()->word()];

        $result = $repository->update($topic->id, $data);

        $this->assertNotNull($topic);
        $this->assertEquals($result, 1);
    }

    public function test_delete_topic()
    {
        $topic = Topic::factory()->create();
        $repository = $this->app->make(TopicRepository::class);

        $result = $repository->delete($topic->id);

        $this->assertNotNull($topic);
        $this->assertEquals($result, 1);
    }
}
