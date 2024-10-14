<?php

namespace Tests\Unit\Repositories;

use App\Models\Post;
use App\Models\Topic;
use App\Repositories\PostRepository;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    protected function makeRepository($connection)
    {
        return new PostRepository($connection);
    }

    public function test_get_all_posts()
    {
        Post::factory()->count(3)->create();
        $repository = $this->app->make(PostRepository::class);
        $page = 1;
        $perPage = 10;

        $posts = $repository->getAll($page, $perPage);

        $this->assertCount(3, $posts);
    }

    public function test_get_post_by_id()
    {
        $post = Post::factory()->create();
        $repository = $this->app->make(PostRepository::class);

        $post = $repository->getById($post->id);

        $this->assertNotNull($post);
    }

    public function test_create_post()
    {
        $repository = $this->app->make(PostRepository::class);
        $topic = Topic::factory()->create();
        $data = ['title' => fake()->word(), 'content' => fake()->sentence(), 'topic_id' => $topic->id];

        $post = $repository->create($data);

        $this->assertNotNull($post);
        $this->assertEquals($data['title'], $post->title);
    }

    public function test_update_post()
    {
        $topic = Topic::factory()->create();
        $post = $topic->posts()->save(Post::factory()->make());
        $repository = $this->app->make(PostRepository::class);
        $data = ['title' => fake()->word()];

        $result = $repository->update($post->id, $data);

        $this->assertNotNull($result);
        $this->assertEquals($result, 1);
    }

    public function test_delete_post()
    {
        $topic = Topic::factory()->create();
        $post = $topic->posts()->save(Post::factory()->make());
        $repository = $this->app->make(PostRepository::class);

        $result = $repository->delete($post->id);

        $this->assertNotNull($result);
        $this->assertEquals($result, 1);
    }
}
