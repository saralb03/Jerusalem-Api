<?php
namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use App\Enums\ServiceType;
use Illuminate\Validation\Rule;

class DetailsValidator
{
    public static function validate(array $dto)
    {
        $validator = Validator::make($dto, [
            'personal_id' => 'required|integer|digits:9',
            'personal_number' => 'required|integer|digits:7',
            'ranks' => 'required|string',
            'surname' => 'required|string',
            'first_name' => 'required|string',
            'department' => 'nullable|string',
            'division' => 'required|string',
            'service_type' => [
                'required',
                'string',
                Rule::in(array_column(ServiceType::cases(), 'value'))
            ],
            'date_of_birth' => 'required|date_format:d.m.Y',
            'service_type_code' => 'required|integer',
            'security_class_start_date' => 'required|date_format:d.m.Y',
            'service_start_date' => 'required|date_format:d.m.Y',
            'solider_type' => 'required|string',
            'age' => 'required|integer',
            'classification' => 'required|integer',
            'phone_number' => 'required|string',
        ]);

        return $validator->passes();
    }
}