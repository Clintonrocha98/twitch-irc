<?php

declare(strict_types=1);

namespace ClintonRocha\TwitchIrc\Parser;

class IrcRawMessage
{
    public ?string $rawTags = null;

    public ?string $prefix = null;

    public string $command;

    public array $params = [];

    public ?string $text = null;
}
