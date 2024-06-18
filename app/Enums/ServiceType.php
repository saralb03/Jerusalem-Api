<?php

namespace App\Enums;

enum ServiceType:string
{
    case MISSION_CIVILAN = 'אזרח משימתי';
    case REGULARITY = 'קבע';
    case DUTY = 'חובה';
    case DUTY_REGULARITY = 'חובה בתנאי קבע';
    case DISCHARGE = 'פטורים';
    case RESERVES = 'מלואים';
    case VOLUNTERR_RESERVES = 'מלואים מתנדבים';
}