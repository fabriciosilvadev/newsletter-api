<?php

namespace Tests\Feature\Http\Api;

use App\Models\Post;
use App\Models\Topic;
use Tests\HasAuthenticatedUser;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use HasAuthenticatedUser;

    public function test_unauthorized_request(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertStatus(401);
    }

    public function test_authorized_request(): void
    {
        $this->setupUser();

        $response = $this->get(route('posts.index'));

        $response->assertStatus(200);
    }

    public function test_get_paginated_posts(): void
    {
        $this->setupUser();
        Post::factory(6)->create();
        $page = 2;
        $perPage = 5;

        $response = $this->get(route('posts.index', [
            'page' => $page,
            'per_page' => $perPage,
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'topic',
                    'creator',
                ],
            ],
        ]);
        $response->assertJsonStructure([
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
        ]);
    }

    public function test_get_single_post(): void
    {
        $this->setupUser();
        $post = Post::factory()->create(['creator_id' => $this->user->id]);

        $response = $this->get(route('posts.show', $post->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'topic' => [
                    'id' => $post->topic->id,
                    'name' => $post->topic->name,
                ],
                'creator' => [
                    'id' => $post->creator->id,
                    'name' => $post->creator->name,
                ],
            ],
        ]);
    }

    public function test_create_with_admin_permission_post(): void
    {
        $this->setupUser(isAdmin: true);
        $topic = Topic::factory()->create();
        $data = [
            'title' => 'Post Title',
            'content' => 'Post Content',
            'topic_id' => $topic->id,
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'title' => $data['title'],
                'content' => $data['content'],
                'topic' => [
                    'id' => $topic->id,
                    'name' => $topic->name,
                ],
                'creator' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
            ],
        ]);
    }

    public function test_create_without_admin_permission_post(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->create();
        $data = [
            'title' => 'Post Title',
            'content' => 'Post Content',
            'topic_id' => $topic->id,
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertStatus(403);
    }

    public function test_update_post_with_admin_permission(): void
    {
        $this->setupUser(isAdmin: true);
        $topic = Topic::factory()->create();
        $post = Post::factory()->create(['creator_id' => $this->user->id, 'topic_id' => $topic->id]);
        $data = [
            'title' => 'Updated Post Title',
            'content' => 'Updated Post Content',
            'topic_id' => $topic->id,
        ];

        $response = $this->put(route('posts.update', $post->id), $data);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'title' => $data['title'],
                'content' => $data['content'],
                'topic' => [
                    'id' => $post->topic->id,
                    'name' => $post->topic->name,
                ],
                'creator' => [
                    'id' => $post->creator->id,
                    'name' => $post->creator->name,
                ],
            ],
        ]);
    }

    public function test_update_post_without_admin_permission(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->create();
        $post = Post::factory()->create(['creator_id' => $this->user->id, 'topic_id' => $topic->id]);
        $data = [
            'title' => 'Updated Post Title',
            'content' => 'Updated Post Content',
            'topic_id' => $topic->id,
        ];

        $response = $this->put(route('posts.update', $post->id), $data);

        $response->assertStatus(403);
    }

    public function test_delete_post_without_admin_permission(): void
    {
        $this->setupUser();
        $post = Post::factory()->create(['creator_id' => $this->user->id]);

        $response = $this->delete(route('posts.destroy', $post->id));

        $response->assertStatus(403);
    }

    public function test_delete_post_with_admin_permission(): void
    {
        $this->setupUser(isAdmin: true);
        $post = Post::factory()->create();

        $response = $this->delete(route('posts.destroy', $post->id));

        $response->assertStatus(200);
    }
}
