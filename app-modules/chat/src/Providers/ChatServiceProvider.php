<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Providers;

use ClintonRocha\Chat\Commands\RankChatCommand;
use ClintonRocha\Chat\Contracts\ChatCommand;
use ClintonRocha\Chat\Listeners\ChatCommandListener;
use ClintonRocha\TwitchIrc\Client\TwitchIrcClient;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TwitchIrcClient::class, fn () => new TwitchIrcClient(
            server: config('twitch.irc.server'),
            port: config()->integer('twitch.irc.port'),
            token: config('twitch.irc.token'),
            nick: config('twitch.irc.nick'),
            channel: config('twitch.irc.channel'),
        ));

        $this->app->tag([
            RankChatCommand::class,
        ], ChatCommand::class, 'chat-commands');

        $this->app->bind(ChatCommandListener::class, fn ($app) => new ChatCommandListener(
            $app->tagged('chat-commands')
        ));
    }
}
