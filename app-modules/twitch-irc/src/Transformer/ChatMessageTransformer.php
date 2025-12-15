<?php

declare(strict_types=1);

namespace ClintonRocha\TwitchIrc\Transformer;

use ClintonRocha\Chat\Domain\ChatMessage;
use ClintonRocha\Chat\Domain\ChatUser;
use ClintonRocha\TwitchIrc\Parser\IrcRawMessage;

class ChatMessageTransformer
{
    public function transform(IrcRawMessage $raw): ChatMessage
    {
        $tags = $this->parseTags($raw->rawTags ?? '');
        [$nick, $user, $host] = $this->parsePrefix($raw->prefix ?? '');

        $userInfo = new ChatUser(
            id: $tags['user-id'] ?? '',
            name: $user,
            displayName: $tags['display-name'] ?? '',
            color: $tags['color'] ?? null,
        );

        return new ChatMessage(
            channel: $raw->params[0] ?? '',
            command: $raw->command,
            text: $raw->text ?? '',
            tags: $tags,
            params: $raw->params,
            user: $userInfo,
        );
    }

    private function parseTags(string $raw): array
    {
        $tags = [];
        foreach (explode(';', $raw) as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, null);
            $tags[$key] = $value;
        }

        return $tags;
    }

    private function parsePrefix(string $prefix): array
    {
        if ($prefix === '') {
            return ['', '', ''];
        }

        [$nick, $rest] = str_contains($prefix, '!')
            ? explode('!', $prefix, 2)
            : [$prefix, ''];

        [$user, $host] = str_contains($rest, '@')
            ? explode('@', $rest, 2)
            : [$rest, ''];

        return [$nick, $user, $host];
    }
}
