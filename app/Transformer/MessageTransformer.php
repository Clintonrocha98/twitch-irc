<?php

namespace App\Transformer;

use App\Domain\Message;
use App\Domain\UserInfo;
use App\Parser\RawMessage;

class MessageTransformer
{
    public function transform(RawMessage $raw): Message
    {
        $tags = $this->parseTags($raw->rawTags ?? '');
        [$nick, $user, $host] = $this->parsePrefix($raw->prefix ?? '');

        $userInfo = new UserInfo(
            id: $tags['user-id'] ?? '',
            name: $user,
            displayName: $tags['display-name'] ?? '',
            color: $tags['color'] ?? null,
        );

        return new Message(
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
