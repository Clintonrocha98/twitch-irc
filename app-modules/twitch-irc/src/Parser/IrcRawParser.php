<?php

declare(strict_types=1);

namespace ClintonRocha\TwitchIrc\Parser;

class IrcRawParser
{
    public function parse(string $line): IrcRawMessage
    {
        $raw = new IrcRawMessage;

        if (str_starts_with($line, '@')) {
            [$tagPart, $line] = explode(' ', $line, 2);
            $raw->rawTags = mb_substr($tagPart, 1);
        }

        if (str_starts_with($line, ':')) {
            [$prefix, $line] = explode(' ', $line, 2);
            $raw->prefix = mb_substr($prefix, 1);
        }

        if (str_contains($line, ' :')) {
            [$header, $text] = explode(' :', $line, 2);
            $raw->text = $text;
        } else {
            $header = $line;
        }

        $parts = explode(' ', $header);
        $raw->command = array_shift($parts);
        $raw->params = $parts;

        return $raw;
    }
}
