<?php

namespace App\Parser;

use App\Transformer\MessageTransformer;
use App\Domain\Message;

readonly class MessageParser
{
    public function __construct(
        private RawParser $rawParser,
        private MessageTransformer $transformer,
    ) {
    }

    public function handle(string $data): Message
    {
        $raw = $this->rawParser->parse($data);
        return $this->transformer->transform($raw);
    }
}
