<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Actions;

use ClintonRocha\Chat\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class GenerateChannelRanking
{
    public function topMessages(string $channel, int $limit = 3)
    {
        return Cache::remember(
            $this->cacheKey($channel, $limit),
            now()->addSeconds(30),
            fn () => $this->queryTopMessages($channel, $limit)
        );
    }

    private function queryTopMessages(string $channel, int $limit): Collection
    {
        return Message::query()
            ->selectRaw('provider_user_id, COUNT(*) as total')
            ->where('channel', $channel)
            ->groupBy('provider_user_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with('providerUser')
            ->get();
    }

    private function cacheKey(string $channel, int $limit): string
    {
        return sprintf('rank:messages:%s:%d', $channel, $limit);
    }
}
