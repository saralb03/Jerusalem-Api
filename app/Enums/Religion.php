<?php

namespace App\Enums;

enum Religion: string
{
    case JEW = "יהודי";
    case BEDOUIN = "בדואי";
    case DRUZE = "דרוזי";
    case CHRISTIAN = "נוצרי";
    case CHRISTIAN_ARAB = "ערבי נוצרי";
    case MOSLEM = "מוסלמי";
    case UNKNOWN = "לא ידוע";

    // public static function fromValue($value): self
    // {
    //     return match ($value) {
    //         'יהודי' => self::JEW,
    //         'בדואי' => self::BEDOUIN,
    //         'דרוזי' => self::DRUZE,
    //         'נוצרי' => self::CHRISTIAN,
    //         'ערבי נוצרי' => self::CHRISTIAN_ARAB,
    //         'מוסלמי' => self::MOSLEM,
    //         default => new self('לא ידוע'), // Assuming "לא ידוע" means "unknown"
    //     };
    // }
}