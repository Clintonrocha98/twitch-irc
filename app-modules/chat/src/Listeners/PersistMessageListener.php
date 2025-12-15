<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Listeners;

use ClintonRocha\Chat\Events\ChatMessageReceived;
use ClintonRocha\Chat\Models\ProviderUser;

class PersistMessageListener
{
    public function handle(ChatMessageReceived $event): void
    {
        $user = ProviderUser::query()->firstOrCreate(
            [
                'provider_name' => $event->channel,
                'provider_user_id' => $event->providerUserId,
            ],
            [
                'username' => $event->username,
            ]
        );

        $user->messages()->create([
            'provider_user_id' => $event->providerUserId,
            'channel' => $event->channel,
            'message' => $event->message,
            'xp' => $event->xp,
            'sent_at' => now(),
        ]);
    }
}
