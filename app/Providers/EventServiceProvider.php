<?php

namespace App\Providers;

use App\Events\PostCreatedEvent;
use App\Listeners\NotifyUsersOnPostCreationListener;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PostCreatedEvent::class => [
            NotifyUsersOnPostCreationListener::class,
        ],
    ];
}
