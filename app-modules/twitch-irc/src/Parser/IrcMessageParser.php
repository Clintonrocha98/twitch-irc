<?php

declare(strict_types=1);

namespace ClintonRocha\TwitchIrc\Parser;

use ClintonRocha\Chat\Domain\ChatMessage;
use ClintonRocha\TwitchIrc\Transformer\ChatMessageTransformer;

readonly class IrcMessageParser
{
    public function __construct(
        private IrcRawParser $rawParser,
        private ChatMessageTransformer $transformer,
    ) {}

    public function handle(string $data): ChatMessage
    {
        $raw = $this->rawParser->parse($data);

        return $this->transformer->transform($raw);
    }
}
