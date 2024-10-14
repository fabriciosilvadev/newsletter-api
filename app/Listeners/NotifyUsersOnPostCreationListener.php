<?php

namespace App\Listeners;

use App\Events\PostCreatedEvent;
use App\Notifications\PostCreatedNotification;
use App\Services\TopicSubscriptionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyUsersOnPostCreationListener implements ShouldQueue
{
    public function __construct(private TopicSubscriptionService $topicSubscriptionService) {}

    public function handle(PostCreatedEvent $event): void
    {
        $post = $event->post;
        $users = $this->topicSubscriptionService->getAllSubscribedUsers($post->topic_id);
        Notification::send($users, new PostCreatedNotification($post));
    }
}
