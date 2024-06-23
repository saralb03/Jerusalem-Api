<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use App\Enums\ServiceType;
use App\Models\Division;
use Illuminate\Validation\Rule;

class EmployeeValidator
{
    public static function validate(array $dto)
    {
        $divisionNames = Division::pluck('name')
            ->merge(Division::pluck('invalid_name'))->toArray();

            
        $validator = Validator::make($dto, [
            'personal_id' => 'required|string|max:9|regex:/^\d+$/',
            'personal_number' => 'required|integer|digits:7|regex:/^\d+$/',
            'ranks' => 'required|string',
            'surname' => 'required|string',
            'first_name' => 'required|string',
            'department' => 'nullable|string',
            'branch' => 'nullable|string',
            'section' => 'nullable|string',
            'division' => [
                'required',
                'string',
                Rule::in($divisionNames),
            ],
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
            'phone_number' => 'nullable',
            'population_id' => 'required|integer'
        ]);

        return $validator->passes();
    }
}
