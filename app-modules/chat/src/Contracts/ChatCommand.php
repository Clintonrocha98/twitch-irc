<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Contracts;

use ClintonRocha\Chat\Events\ChatMessageReceived;

interface ChatCommand
{
    public function matches(string $message): bool;

    public function execute(ChatMessageReceived $event): void;
}
