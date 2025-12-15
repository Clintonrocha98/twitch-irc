<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Commands;

use ClintonRocha\Chat\Actions\GenerateChannelRanking;
use ClintonRocha\Chat\Contracts\ChatCommand;
use ClintonRocha\Chat\Events\ChatMessageReceived;
use ClintonRocha\TwitchIrc\Client\TwitchIrcClient;

readonly class RankChatCommand implements ChatCommand
{
    public function __construct(
        private GenerateChannelRanking $rankingAction,
        private TwitchIrcClient $client
    ) {}

    public function matches(string $message): bool
    {
        return mb_trim($message) === '!rank';
    }

    public function execute(ChatMessageReceived $event): void
    {
        $top = $this->rankingAction->topMessages($event->channel);

        if ($top->isEmpty()) {
            $this->client->sendMessage(
                $event->channel,
                'Ainda não há mensagens suficientes para gerar o ranking.'
            );

            return;
        }

        $parts = [];

        foreach ($top as $index => $row) {
            $parts[] = sprintf(
                '%dº %s (%d msgs)',
                $index + 1,
                $row->providerUser->username,
                $row->total
            );
        }

        $this->client->sendMessage(
            $event->channel,
            'Top chatters: '.implode(' | ', $parts)
        );
    }
}
