<?php

use ClintonRocha\Chat\Domain\ChatUser;
use ClintonRocha\TwitchIrc\Transformer\ChatMessageTransformer;
use ClintonRocha\TwitchIrc\Parser\RawMessage;

it('transforms a RawMessage into a ChatMessage domain object', function () {
    $transformer = new ChatMessageTransformer();

    $raw = new RawMessage();
    $raw->rawTags = 'user-id=22222;display-name=Example;color=#000000';
    $raw->prefix = 'example!user@example.tmi.twitch.tv';
    $raw->command = 'PRIVMSG';
    $raw->params = ['#channel'];
    $raw->text = 'hi there';

    $chatMessage = $transformer->transform($raw);

    expect($chatMessage->channel)->toBe('#channel')
        ->and($chatMessage->command)->toBe('PRIVMSG')
        ->and($chatMessage->text)->toBe('hi there')
        ->and($chatMessage->user)->toBeInstanceOf(ChatUser::class)
        ->and($chatMessage->user->id)->toBe('22222')
        ->and($chatMessage->user->displayName)->toBe('Example');
});
