<?php

namespace Tests\Feature\Notifications;

use App\Models\Post;
use App\Models\Topic;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PostCreatedNotificationTest extends TestCase
{
    public function test_post_created_notification()
    {
        Notification::fake();
        $topic = Topic::factory()->create();
        $post = Post::factory()->create(['topic_id' => $topic->id]);
        $user = User::factory()->create();

        $user->notify(new PostCreatedNotification($post));

        Notification::assertSentTo($user, PostCreatedNotification::class, function ($notification) use ($user, $post) {
            $subject = "{$post->topic->name}: {$post->title}";

            return $notification->toMail($user)->subject === $subject;
        });
    }
}
