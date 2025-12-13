<?php

namespace App\Parser;

class RawMessage
{
    public ?string $rawTags = null;
    public ?string $prefix = null;
    public string $command;
    public array $params = [];
    public ?string $text = null;
}
