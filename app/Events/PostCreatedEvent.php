<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Post $post) {}
}
