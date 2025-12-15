<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Contracts;

interface IrcClient
{
    public function connect(): void;

    public function listen(callable $onMessage): void;

    public function disconnect(): void;
}
