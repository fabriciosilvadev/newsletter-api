<?php

namespace Tests\Feature\Listeners;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyUsersOnPostCreationListenerTest extends TestCase
{
    public function test_create_notification_on_post_created_event(): void
    {
        Notification::fake();

        $topic = Topic::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $topic->users()->attach($user1);

        Post::factory()->create(['topic_id' => $topic->id]);

        Event::fake();
        Notification::assertSentTo($user1, PostCreatedNotification::class);
        Notification::assertNotSentTo($user2, PostCreatedNotification::class);
    }
}
