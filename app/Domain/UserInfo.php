<?php

namespace App\Domain;

class UserInfo
{
    public function __construct(
        public string $id,
        public string $name,
        public string $displayName,
        public ?string $color
    ) {
    }

    public function hasColor(): bool
    {
        return !empty($this->color);
    }
}
