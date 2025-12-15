<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Services;

class MessageXpCalculator
{
    public function calculate(string $message): int
    {
        $length = mb_strlen(mb_trim($message));

        return match (true) {
            $length === 0 => 0,
            $length <= 5 => 1,
            $length <= 20 => 2,
            $length <= 50 => 5,
            default => 10,
        };
    }
}
