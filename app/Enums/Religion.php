<?php

namespace App\Enums;

enum Religion: string
{
    case JEW = 'יהודי';
    case BEDOUIN = 'בדואי';
    case DRUZE = 'דרוזי';
    case CHRISTIAN = 'נוצרי';
    case CHRISTIAN_ARAB = 'ערבי נוצרי';
    case MOSLEM = 'מוסלמי';
    case UNKNOWN = 'לא ידוע';

    public static function validateReligion(?string $value): ?string
    {
        return match ($value) {
            self::JEW->value, self::BEDOUIN->value, self::DRUZE->value,
            self::CHRISTIAN->value, self::CHRISTIAN_ARAB->value, self::MOSLEM->value,
            self::UNKNOWN->value => $value,
            default => null,
        };
    }
}