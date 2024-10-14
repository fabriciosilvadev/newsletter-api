<?php

namespace Tests\Unit\Services;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Services\PostService;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    protected function makeService($connection)
    {
        return new PostService(new PostRepository($connection));
    }

    public function test_get_all_posts()
    {
        Post::factory()->count(3)->create();
        $service = $this->app->make(PostService::class);
        $page = 1;
        $perPage = 10;

        $posts = $service->getAll($page, $perPage);

        $this->assertCount(3, $posts);
    }

    public function test_show_post_by_id()
    {
        $post = Post::factory()->create();
        $service = $this->app->make(PostService::class);

        $post = $service->show($post->id);

        $this->assertNotNull($post);
    }

    public function test_create_post()
    {
        $user = User::factory()->create();
        $topic = Topic::factory()->create();
        $service = $this->app->make(PostService::class);
        $data = ['title' => fake()->word(), 'content' => fake()->sentence(), 'topic_id' => $topic->id, 'user_id' => $user->id];

        $post = $service->create($data, $user);

        $this->assertNotNull($post);
        $this->assertEquals($data['title'], $post->title);
    }

    public function test_update_post()
    {
        $post = Post::factory()->create();
        $service = $this->app->make(PostService::class);
        $data = ['title' => fake()->word()];

        $result = $service->update($post->id, $data);

        $this->assertNotNull($result);
        $this->assertEquals($result['id'], $post->id);
    }

    public function test_delete_post()
    {
        $post = Post::factory()->create();
        $service = $this->app->make(PostService::class);

        $result = $service->delete($post->id);

        $this->assertNotNull($result);
        $this->assertEquals($result, 1);
    }
}
