<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
class IndexEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Authorization logic, if needed (e.g., user roles/permissions)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $fillableColumns = [
            'personal_id',
            'personal_number',
            'rank',
            'surname',
            'first_name',
            'department',
            'branch',
            'section',
            'division',
            'date_of_birth',
            'security_class_start_date',
            'service_start_date',
            'age',
            'classification',
            'classification_name',
            'phone_number',
        ];

        return [
            'columns' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) use ($fillableColumns) {
                    $requestedColumns = explode(',', $value);
                    foreach ($requestedColumns as $column) {
                        $snakeCaseColumn = Str::snake($column);
                        if (!in_array($snakeCaseColumn, $fillableColumns)) {
                            $fail("העמודה '$column' שנבחרה אינה חוקית.");
                        }
                    }
                },
            ],
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'columns.string' => 'הפרמטר של העמודות צריך להיות מחרוזת.',
            'columns.array' => 'הפרמטר של העמודות צריך להיות מערך.',
            'columns.*' => 'העמודה שנבחרה אינה חוקית.',
        ];
    }
}