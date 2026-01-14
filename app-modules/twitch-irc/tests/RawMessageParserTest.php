<?php

use ClintonRocha\TwitchIrc\Parser\RawMessage;
use ClintonRocha\TwitchIrc\Parser\RawMessageParser;

it('parses a raw irc line into a RawMessage', function () {
    $parser = new RawMessageParser();

    $line = "@badge-info=subscriber/10;badges=subscriber/10;color=#1E90FF;display-name=exampleUser;emotes=;id=12345;mod=0;room-id=99999;subscriber=1;user-id=11111 :exampleUser!exampleUser@exampleUser.tmi.twitch.tv PRIVMSG #channel :hello world";

    $raw = $parser->parse($line);

    expect($raw)->toBeInstanceOf(RawMessage::class)
        ->and($raw->rawTags)->toBeString()
        ->and($raw->prefix)->toBe('exampleUser!exampleUser@exampleUser.tmi.twitch.tv')
        ->and($raw->command)->toBe('PRIVMSG')
        ->and($raw->params)->toBeArray()
        ->and($raw->text)->toBe('hello world');
});
