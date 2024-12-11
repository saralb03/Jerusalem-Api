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
        return match ($this) {
            self::CIVILAN =>  'c',
            self::DUTY, self::DISCHARGE, self::REGULARITY => 's',
            self::RESERVES => 'm',
            self::CONTRACT_WORKER => 'o',
            default => null,
        };
    }

    public static function getValid(?string $population): ?string
    {
        return match ($population) {
            'אזרח', 'עובד צה"ל' => self::CIVILAN->value,
            'קבע', 'חובה בתנאי קבע' => self::REGULARITY->value,
            'חובה' => self::DUTY->value,
            'פטורים' => self::DISCHARGE->value,
            'מילואים', 'מילואים מתנדבים' => self::RESERVES->value,
            'עובד קבלן', 'יועץ' => self::CONTRACT_WORKER->value,
            default => null,
        };
    }
}
