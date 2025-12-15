<?php

declare(strict_types=1);

namespace App\Console\Commands;

use ClintonRocha\Chat\Enums\MessageProvider;
use ClintonRocha\Chat\Events\ChatMessageReceived;
use ClintonRocha\Chat\Services\MessageXpCalculator;
use ClintonRocha\TwitchIrc\Client\TwitchIrcClient;
use ClintonRocha\TwitchIrc\Parser\IrcMessageParser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

class Irc extends Command
{
    protected $signature = 'app:irc';

    protected $description = 'Command description';

    public function handle(
        TwitchIrcClient $client,
        IrcMessageParser $parser,
        MessageXpCalculator $xpCalculator
    ): void {
        $client->connect();

        $client->listen(function (string $line) use ($xpCalculator, $parser): void {
            $message = $parser->handle($line);

            if (! $message->isPrivMsg()) {
                return;
            }

            $xp = $xpCalculator->calculate($message->text);

            event(new ChatMessageReceived(
                $message->user->id,
                MessageProvider::Twitch,
                $message->username(),
                $message->channel,
                $message->text,
                $xp,
                Date::now()->getTimestamp()
            ));

            $username = $message->username();
            $channel = $message->channel;
            $text = $message->text;

            if ($message->user->hasColor()) {
                $username = sprintf('<fg=%s>%s</>', $message->user->color, $username);
            }

            $time = now()->format('H:i');

            $formatted = sprintf(
                '<fg=magenta>[%s]</> <fg=bright-green>#%s</> %s: %s',
                $time,
                mb_ltrim($channel, '#'),
                $username,
                $text
            );

            $this->line($formatted);
        });

        $client->disconnect();
    }
}
