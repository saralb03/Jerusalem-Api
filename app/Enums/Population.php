<?php

namespace App\Enums;

enum Population: string
{
    case MISSION_CIVILAN = 'אזרח משימתי';
    case REGULARITY = 'קבע';
    case DUTY = 'חובה';
    case DUTY_REGULARITY = 'חובה בתנאי קבע';
    case DISCHARGE = 'פטורים';
    case RESERVES = 'מלואים';
    case VOLUNTERR_RESERVES = 'מלואים מתנדבים';

    public function getPrefix(): ?string
    {
        switch ($this) {
            case self::MISSION_CIVILAN:
                return 'c';
            case self::DUTY:
            case self::DUTY_REGULARITY:
            case self::DISCHARGE:
            case self::REGULARITY:
                return 's';
            case self::RESERVES:
            case self::VOLUNTERR_RESERVES:
                return 'm';
            default:
                return null;
        }
    }
}
