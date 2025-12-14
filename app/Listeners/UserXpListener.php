<?php

namespace App\Listeners;

use App\Events\ChatMessageReceived;

class UserXpListener
{
    private array $xp = [];

    public function handle(ChatMessageReceived $event): void
    {
        $key = "{$event->channel}:{$event->userId}";
        $this->xp[$key] = ($this->xp[$key] ?? 0) + 1;
    }
}
