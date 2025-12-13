<?php

namespace App\Parser;

class RawParser
{
    public function parse(string $line): RawMessage
    {
        $raw = new RawMessage;

        if (str_starts_with($line, '@')) {
            [$tagPart, $line] = explode(' ', $line, 2);
            $raw->rawTags = substr($tagPart, 1);
        }

        if (str_starts_with($line, ':')) {
            [$prefix, $line] = explode(' ', $line, 2);
            $raw->prefix = substr($prefix, 1);
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
