<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Listeners;

use ClintonRocha\Chat\Contracts\ChatCommand;
use ClintonRocha\Chat\Events\ChatMessageReceived;

readonly class ChatCommandListener
{
    /**
     * @param  iterable<ChatCommand>  $commands
     */
    public function __construct(
        private iterable $commands
    ) {}

    public function handle(ChatMessageReceived $event): void
    {
        foreach ($this->commands as $command) {
            if ($command->matches($event->message)) {
                $command->execute($event);

                return;
            }
        }
    }
}
