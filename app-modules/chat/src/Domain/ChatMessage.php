<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Domain;

class ChatMessage
{
    public function __construct(
        public string $channel,
        public string $command,
        public string $text,
        public array $tags,
        public array $params,
        public ChatUser $user,
    ) {}

    public static function make(
        string $channel,
        string $command,
        string $text,
        array $tags,
        array $params,
        ChatUser $user,
    ): self {
        return new self(
            $channel,
            $command,
            $text,
            $tags,
            $params,
            $user,
        );
    }

    public function isPrivMsg(): bool
    {
        return $this->command === 'PRIVMSG';
    }

    public function isFromSubscriber(): bool
    {
        return ($this->tags['subscriber'] ?? '0') === '1';
    }

    public function username(): string
    {
        return $this->user->displayName ?: $this->user->name;
    }
}
