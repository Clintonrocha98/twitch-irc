<?php

namespace App\Listeners;

use App\Events\ChatMessageReceived;

class MessageCounterListener
{
    private array $counts = [];


    public function handle(ChatMessageReceived $event): void
    {
        $key = "{$event->channel}:{$event->userId}";
        $this->counts[$key] = ($this->counts[$key] ?? 0) + 1;
        echo "$this->counts[$key]";
    }

    public function top(string $channel, int $limit = 3): array
    {
        $filtered = array_filter(
            $this->counts,
            fn($_, $key) => str_starts_with($key, "$channel:"),
            ARRAY_FILTER_USE_BOTH
        );
        arsort($filtered);

        return array_slice($filtered, 0, $limit, true);
    }
}
