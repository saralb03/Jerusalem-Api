<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Enums\Status;
use App\Enums\ValidColumns;
use App\Models\Details;
use App\Models\Employee;
use App\Validators\EmployeeValidator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function index(array $requestedColumns): Collection
    {
        $validColumns = [];
        foreach ($requestedColumns as $column) {
            $validColumns[] = ValidColumns::from($column)->toSnake();
        }

        return Employee::join('details', 'employees.id', '=', 'details.employee_id')
            ->when($validColumns, function ($query) use ($validColumns) {
                $query->select($validColumns);
            })
            ->get();
    }

    private static function getKey(string $hebrewKey): string
    {
        return match ($hebrewKey) {
            'תז', 'ת"ז' => 'personal_id',
            'מספר אישי' => 'personal_number',
            'שם פרטי' => 'first_name',
            'שם משפחה' => 'surname',
            'סוג שרות', 'סוג שירות', 'אוכלוסיה' => 'population',
            'דרגה' => 'rank',
            'מחלקה' => 'department',
            'ענף' => 'branch',
            'מדור' => 'section',
            'יחידת רישום' => 'division',
            'תאריך לידה' => 'date_of_birth',
            'תאריך מתן סיווג נוכחי' => 'security_class_start_date',
            'גיל' => 'age',
            'סב"ט נוכחי' => 'classification',
            'טלפון' => 'phone_number',
            'קידומת מספר טלפון',  => 'prefix_phone',
            'מספר טלפון' => 'suffix_phone',
            'מקצוע' => 'profession',
            'מין' => 'gender',
            'דת' => 'religion',
            'ארץ לידה' => 'country_of_birth',
            'תאריך שחרור' => 'release_date',
            'שם משתמש' => 'user_name',
            default => '',
        };
    }

    public function update(string $filePath, bool $createDetails): Status
    {
        if (!file_exists($filePath)) {
            return Status::NOT_FOUND;
        }

        try {
            $fileContents = file($filePath);
            $headers = str_getcsv(array_shift($fileContents));
            $englishHeaders = collect($headers)->map(function ($header) {
                return EmployeeService::getKey($header);
            })->toArray();
            
            DB::beginTransaction();

            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                $employeeData = array_combine($englishHeaders, $data);
                $employeeDTO = new EmployeeDTO($employeeData);
                
                if (!EmployeeValidator::validate((array) $employeeDTO)) {
                    continue;
                }

                $employeeDTO->convertDTO();
                $employee = Employee::updateOrCreate(
                    ['personal_number' => $employeeDTO->personal_number],
                    (array)$employeeDTO
                );

                if (!$createDetails) {
                    continue;
                }

                $employeeDTO->employee_id = $employee->id;
                Details::updateOrCreate(
                    ['employee_id' => $employeeDTO->employee_id],
                    (array)$employeeDTO
                );
            }
            DB::commit();
            return Status::OK;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        return Status::ERROR;
    }
}
