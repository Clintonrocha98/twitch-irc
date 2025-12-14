<?php

namespace App\Listeners;

use App\Events\UserJoinedChannel;
use App\Events\UserLeftChannel;

class UserSessionListener
{
    private array $sessions = [];

    public function handleJoin(UserJoinedChannel $event): void
    {
        $this->sessions[$event->userId] = $event->timestamp;
    }

    public function handleLeave(UserLeftChannel $event): void
    {
        $start = $this->sessions[$event->userId] ?? null;
        if ($start === null) {
            return;
        }

        $duration = $event->timestamp - $start;
        // acumula tempo
        unset($this->sessions[$event->userId]);
    }
}
