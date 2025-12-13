<?php

namespace App\Providers;

use App\Client\TwitchIrcClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(TwitchIrcClient::class, function () {
            return new TwitchIrcClient(
                server: config('twitch.irc.server'),
                port: config('twitch.irc.port'),
                token: config('twitch.irc.token'),
                nick: config('twitch.irc.nick'),
                channel: config('twitch.irc.channel'),
            );
        });
    }

}
