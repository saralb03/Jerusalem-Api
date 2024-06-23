<?php

namespace App\Enums;

enum ServiceType: string
{
    case MISSION_CIVILAN = 'אזרח משימתי';
    case REGULARITY = 'קבע';
    case DUTY = 'חובה';
    case DUTY_REGULARITY = 'חובה בתנאי קבע';
    case DISCHARGE = 'פטורים';
    case RESERVES = 'מלואים';
    case VOLUNTERR_RESERVES = 'מלואים מתנדבים';

    public function getPrefix(): string
    {
        switch ($this) {
            case self::MISSION_CIVILAN:
                return 'C';
            case self::DUTY:
            case self::DUTY_REGULARITY:
            case self::DISCHARGE:
            case self::REGULARITY:
                return 'S';
            case self::RESERVES:
            case self::VOLUNTERR_RESERVES:
                return 'M';
            default:
                return '';
        }
    }
}
