<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Enums\ValidColumns;

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
        return [
            'columns' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    $requestedColumns = explode(',', $value);
                    foreach ($requestedColumns as $column) {
                        if (!ValidColumns::isValidColumn($column)) {
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
        ];
    }
}
