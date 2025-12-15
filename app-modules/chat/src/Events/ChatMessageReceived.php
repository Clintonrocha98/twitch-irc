<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Events;

use ClintonRocha\Chat\Enums\MessageProvider;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageReceived
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string $providerUserId,
        public MessageProvider $provider,
        public string $username,
        public string $channel,
        public string $message,
        public int $xp,
        public int $timestamp,
    ) {}
}
