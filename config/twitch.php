<?php

declare(strict_types=1);

return [
    'irc' => [
        'server' => env('TWITCH_IRC_SERVER', 'irc.chat.twitch.tv'),
        'port' => env('TWITCH_IRC_PORT', 6697),
        'token' => env('TWITCH_IRC_TOKEN'),
        'nick' => env('TWITCH_IRC_NICK'),
        'channel' => env('TWITCH_IRC_CHANNEL'),
    ],
];
