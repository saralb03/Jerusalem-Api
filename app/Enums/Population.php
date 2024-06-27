<?php

namespace App\Enums;

enum Population: string
{
    case CIVILAN = 'אזרח';
    case REGULARITY = 'קבע';
    case DUTY = 'חובה';
    case DISCHARGE = 'פטורים';
    case RESERVES = 'מלואים';
    case CONTRACT_WORKER = 'עובד קבלן';

    public function getPrefix(): ?string
    {
        switch ($this) {
            case self::CIVILAN:
                return 'c';
            case self::DUTY:
            case self::DISCHARGE:
            case self::REGULARITY:
                return 's';
            case self::RESERVES:
                return 'm';
            case self::CONTRACT_WORKER:
                return 'o';
            default:
                return null;
        }
    }

    public static function getValid($population): ?string
    {
        return match ($population) {
            'אזרח', 'עובד צה"ל' => self::CIVILAN->value,
            'קבע', 'חובה בתנאי קבע' => self::REGULARITY->value,
            'חובה' => self::DUTY->value,
            'פטורים' => self::DISCHARGE->value,
            'מילואים', 'מילואים מתנדבים' => self::RESERVES->value,
            'עובד קבלן' , 'יועץ' => self::CONTRACT_WORKER->value,
            default => null,
        };
    }
}
