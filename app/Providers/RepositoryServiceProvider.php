<?php

namespace App\Providers;

use App\Contracts\PostRepositoryContract;
use App\Contracts\TopicRepositoryContract;
use App\Contracts\UserTopicRepositoryContract;
use App\Repositories\PostRepository;
use App\Repositories\TopicRepository;
use App\Repositories\UserTopicRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TopicRepositoryContract::class, TopicRepository::class);
        $this->app->bind(PostRepositoryContract::class, PostRepository::class);
        $this->app->bind(UserTopicRepositoryContract::class, UserTopicRepository::class);
    }
}
