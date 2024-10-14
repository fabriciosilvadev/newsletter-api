<?php

namespace Tests\Feature\Events;

use App\Events\PostCreatedEvent;
use App\Models\Post;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostCreatedEventTest extends TestCase
{
    public function test_event_dispatched_successfully(): void
    {
        Event::fake();

        $post = Post::factory()->create();

        Event::assertDispatched(function (PostCreatedEvent $event) use ($post) {
            return $event->post->id === $post->id;
        });
    }
}
