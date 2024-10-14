<?php

namespace Tests\Feature\Http\Api;

use App\Models\Topic;
use Tests\HasAuthenticatedUser;
use Tests\TestCase;

class TopicControllerTest extends TestCase
{
    use HasAuthenticatedUser;

    public function test_unauthorized_request(): void
    {
        $response = $this->get(route('topics.index'));

        $response->assertStatus(401);
    }

    public function test_authorized_request(): void
    {
        $this->setupUser();

        $response = $this->get(route('topics.index'));

        $response->assertStatus(200);
    }

    public function test_get_paginated_topics(): void
    {
        $this->setupUser();
        Topic::factory(6)->create();
        $page = 2;
        $perPage = 5;

        $response = $this->get(route('topics.index', [
            'page' => $page,
            'per_page' => $perPage,
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'creator',
                    'posts_count',
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
        $response->assertJsonStructure([
            'meta' => [
                'current_page',
                'from',
                'path',
                'per_page',
                'to',
            ],
        ]);
    }

    public function test_get_topic_by_id(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->create();

        $response = $this->get(route('topics.show', $topic->id));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $topic->id,
                'name' => $topic->name,
                'creator' => $topic->creator,
                'posts_count' => $topic->posts_count,
            ],
        ]);
    }

    public function test_create_topic_without_admin_permission(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->make();

        $response = $this->post(route('topics.store'), [
            'name' => $topic->name,
        ]);

        $response->assertStatus(403);
    }

    public function test_create_topic_with_admin_permission(): void
    {
        $this->setupUser(isAdmin: true);
        $topic = Topic::factory()->make();

        $response = $this->post(route('topics.store'), [
            'name' => $topic->name,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'data' => [
                'name' => $topic->name,
                'creator' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'posts_count' => 0,
            ],
        ]);
    }

    public function test_update_topic_without_admin_permission(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->create();
        $newName = 'New Name';

        $response = $this->put(route('topics.update', $topic->id), [
            'name' => $newName,
        ]);

        $response->assertStatus(403);
    }

    public function test_update_topic_with_admin_permission(): void
    {
        $this->setupUser(isAdmin: true);
        $topic = Topic::factory()->create(['creator_id' => $this->user->id]);
        $newName = 'New Name';

        $response = $this->put(route('topics.update', $topic->id), [
            'name' => $newName,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $topic->id,
                'name' => $newName,
                'creator' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ],
                'posts_count' => $topic->posts_count,
            ],
        ]);
    }

    public function test_delete_topic_without_admin_permission(): void
    {
        $this->setupUser();
        $topic = Topic::factory()->create();

        $response = $this->delete(route('topics.destroy', $topic->id));

        $response->assertStatus(403);
    }

    public function test_delete_topic_with_admin_permission(): void
    {
        $this->setupUser(isAdmin: true);
        $topic = Topic::factory()->create();

        $response = $this->delete(route('topics.destroy', $topic->id));

        $response->assertStatus(200);
    }
}
