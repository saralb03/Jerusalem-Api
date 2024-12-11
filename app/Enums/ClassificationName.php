<?php

namespace App\Enums;

enum ClassificationName: int
{
    case SHOS = 1;
    case COMMUNITY_TOP_SECRET = 2;
    case TOP_SECRET = 3;
    case SECRET = 4;
    case RESERVED = 5;

    public static function toHebrew(?string $classification): ?string
    {
        return match ($classification) {
            self::SHOS->value => 'שו"ס',
            self::COMMUNITY_TOP_SECRET->value => 'סודי ביותר קהילה',
            self::TOP_SECRET->value => 'סודי ביותר',
            self::SECRET->value => 'סודי',
            self::RESERVED->value => 'שמור',
            default => null,
        };
    }
}
