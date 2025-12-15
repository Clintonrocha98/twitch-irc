<?php

declare(strict_types=1);

namespace ClintonRocha\Chat\Enums;

enum MessageProvider: string
{
    case Discord = 'discord';
    case Twitch = 'twitch';
}
