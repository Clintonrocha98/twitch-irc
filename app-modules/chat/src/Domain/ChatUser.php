<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Domain;

class ChatUser
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayName,
        public ?string $color
    ) {}

    public function hasColor(): bool
    {
        return ! in_array($this->color, [null, '', '0'], true);
    }
}
