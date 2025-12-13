<?php
declare(strict_types=1);

namespace App\Contract;

interface IrcClientContract
{
    public function connect(): void;

    public function listen(callable $onMessage): void;

    public function disconnect(): void;
}
