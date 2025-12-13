<?php

namespace App\Console\Commands;

use App\Client\TwitchIrcClient;
use App\Parser\MessageParser;
use Illuminate\Console\Command;

class Irc extends Command
{
    protected $signature = 'app:irc';

    protected $description = 'Command description';

    public function handle(
        TwitchIrcClient $client,
        MessageParser $parser,
    ): void {
        $client->connect();

        $client->listen(function (string $line) use ($parser) {
            $message = $parser->handle($line);

            if (!$message->isPrivMsg()) {
                return;
            }

            $username = $message->username();
            $channel = $message->channel;
            $text = $message->text;

            if ($message->user->hasColor()) {
                $username = "<fg={$message->user->color}>{$username}</>";
            }

            $time = now()->format('H:i');
            $formatted = sprintf(
                "<fg=magenta>[%s]</> <fg=bright-green>#%s</> %s: %s",
                $time,
                ltrim($channel, '#'),
                $username,
                $text
            );

            $this->line($formatted);
        });

        $client->disconnect();
    }
}
