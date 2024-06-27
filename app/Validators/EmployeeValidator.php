<?php
namespace App\Validators;
use App\Enums\Division;
use Illuminate\Support\Facades\Validator;
use App\Enums\Population;
use App\Enums\Rank;
use App\Enums\Religion;
use Illuminate\Validation\Rule;
class EmployeeValidator
{
    public static function validate(array $dto)
    {
        
        $validator = Validator::make($dto, [
            'personal_id' => 'required|string|max:9|regex:/^\d+$/',
            'personal_number' => 'required|string|size:7,9,11',
            'first_name' => 'required|string',
            'surname' => 'required|string',
            'population' => [
                'required',
                'string',
                Rule::in(Population::cases()),
            ],
            'rank' => [
               'nullable',
               'string',
               Rule::in(Rank::cases()),
            ],
            'department' => 'nullable|string',
            'branch' => 'nullable|string',
            'section' => 'nullable|string',
            'division' => [
                'nullable',
                'string',
                Rule::in(Division::cases()),
            ],
            'date_of_birth' => 'nullable',
            'security_class_start_date' => 'nullable',
            'age' => 'nullable|integer',
            'classification' => 'nullable|integer|min:1|max:5',
            'phone_number' => 'nullable|string',
            'profession' => 'nullable|string',
            'gender' => 'nullable|string',
            'religion' => [
                'nullable',
                'string',
                Rule::in(Religion::cases()),
            ],
            'country_of_birth' => 'nullable|string',
            'release_date' => 'nullable',
        ]);

        return $validator->passes();
    }
}