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
}