<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum ValidColumns: string
{
    case PERSONAL_ID = 'personalId';
    case PERSONAL_NUMBER = 'personalNumber';
    case RANK = 'rank';
    case SURNAME = 'surname';
    case FIRST_NAME = 'firstName';
    case DEPARTMENT = 'department';
    case BRANCH = 'branch';
    case SECTION = 'section';
    case DIVISION = 'division';
    case SERVICE_TYPE = 'serviceType';
    case USER_NAME = 'userName';
    case TYPE = 'type';
    case DATE_OF_BIRTH = 'dateOfBirth';
    case SECURITY_CLASS_START_DATE = 'securityClassStartDate';
    case SERVICE_START_DATE = 'service_start_date';
    case SOLIDER_TYPE = 'soliderType';
    case AGE = 'age';
    case CLASSIFICATION = 'classification';
    case CLASSIFICATION_NAME = 'classificationName';
    case PHONE_NUMBER = 'phone_number';
    case POPULATION_ID = 'population_id';

    public function toSnake(): string
    {
        return Str::snake($this->value);
    }

    public static function isValidColumn(string $column): bool
    {
        return in_array($column, array_column(self::cases(), 'value'));
    }
}
