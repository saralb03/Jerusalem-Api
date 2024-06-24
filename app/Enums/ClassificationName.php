<?php

namespace App\Enums;

enum ClassificationName: int
{
    case SHOS = 1;
    case COMMUNITY_TOP_SECRET = 2;
    case TOP_SECRET = 3;
    case SECRET = 4;
    case RESERVED = 5;

    public static function toHebrew(int $classification): ?string {
        switch ($classification) {
            case self::SHOS->value:
                return 'שו"ס';
            case self::COMMUNITY_TOP_SECRET->value:
                return 'סודי ביותר קהילה';
            case self::TOP_SECRET->value:
                return 'סודי ביותר';
            case self::SECRET->value:
                return 'סודי';
            case self::RESERVED->value:
                return 'שמור';
            default:
                return null; // or handle invalid classification
        }
    }
}