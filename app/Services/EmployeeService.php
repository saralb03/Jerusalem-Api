<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Enums\EmployeeType;
use App\Enums\ValidColumns;
use App\Models\Details;
use App\Models\Employee;
use App\Validators\EmployeeValidator;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeService
{
    public function index(array $requestedColumns)
    {
        $validColumns = [];
        foreach ($requestedColumns as $column) {
            if (!ValidColumns::isValidColumn($column)) {
                continue;
            }
            $validColumns[] = ValidColumns::from($column)->toSnake();
        }
        $employees = Employee::leftJoin('details', 'employees.id', '=', 'details.employee_id')
            ->when($validColumns, function ($query) use ($validColumns) {
                $query->select($validColumns);
            })
            ->get();

        return response()->json($employees);
    }

    public function update()
    {
        // $filePath = "C:\\Users\\Emet-Dev-23\\Desktop\\Projects\\employees.csv";
        $filePath = "C:\\Users\\Emet-Dev\\Documents\\New folder\\employees-2.csv";
        $columnMapping = [
            'תז' => 'personal_id',
            'מספר אישי' => 'personal_number',
            'דרגה' => 'ranks',
            'שם משפחה' => 'surname',
            'שם פרטי' => 'first_name',
            'שם משתמש' => 'user_name',
            'מחלקה' => 'department',
            'ענף' => 'branch',
            'מדור' => 'section',
            'יחידה' => 'division',
            'סוג שרות' => 'service_type',
            'תאריך לידה' => 'date_of_birth',
            'קוד סוג ש' => 'service_type_code',
            'תאריך תחילת סוש' => 'security_class_start_date',
            'תאריך תחילת שרות' => 'service_start_date',
            'סוג חייל' => 'solider_type',
            'גיל' => 'age',
            'סיווג' => 'classification',
            'מזהה אוכלוסיה' => 'population_id',
            'טלפון' => 'phone_number',
        ];

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }
        $fileContents = file($filePath);
        $headers = str_getcsv(array_shift($fileContents));
        $dbHeaders = array_map(function ($header) use ($columnMapping) {
            return $columnMapping[$header] ?? null;
        }, $headers);
        $employeeDTOs = [];
        $csvPersonalIds = [];
        $csvPersonalNumbers = [];

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $employeeData = array_combine($dbHeaders, $data);

            if (!EmployeeValidator::validate($employeeData)) {
                continue;
            }

            $employeeDTO = new EmployeeDTO($employeeData);

            $employeeDTOs[] = $employeeDTO;
            $csvPersonalIds[] = $employeeDTO->personal_id;
            $csvPersonalNumbers[] = $employeeData["personal_number"];
        }

        DB::beginTransaction();
        try {
            $employees = Employee::with('details')
                ->where('type', EmployeeType::REQULAR->value)
                ->whereNotIn('personal_number', $csvPersonalNumbers)->get();
            foreach ($employees as $employee) {
                $employee->details->delete();
                $employee->delete();
            }

            foreach ($employeeDTOs as $dto) {
                $employee = Employee::withTrashed()->updateOrCreate(
                    ['personal_number' => $dto->personal_number],
                    [
                        'user_name' => $dto->user_name,
                        'type' => $dto->type,
                    ]
                );

                $dto->employee_id = $employee->id;
                $details = Details::withTrashed()->updateOrCreate(
                    ['personal_id' => $dto->personal_id],
                    (array)$dto
                );

                if ($employee->trashed()) {
                    $employee->restore();
                    $details->restore();
                }
            }

            DB::commit();
            return response()->json(['message' => 'CSV file imported successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error importing CSV file: ' . $e->getMessage()], 500);
        }
    }

    public function import(File $file)
    {
        if (!$file) {
            return response()->json(['error' => 'No file uploaded.'], 400);
        }
        try {
            $fileContents = file($file->getPathname());

            $headers = str_getcsv(array_shift($fileContents));

            $processedPersonalNumbers = [];

            foreach ($fileContents as $line) {
                $data = str_getcsv($line);

                $rowData = array_combine($headers, $data);

                $employee = Employee::withTrashed()->updateOrCreate(
                    [
                        'personal_number' => $rowData["personal_number"],
                        'type' => EmployeeType::NOT_REQULAR->value,
                    ],
                    [
                        'user_name' => $rowData["user_name"],
                        'type' => EmployeeType::NOT_REQULAR->value,
                    ]
                );

                if ($employee->trashed()) {
                    $employee->restore();
                }

                $processedPersonalNumbers[] = $rowData["personal_number"];
            }

            Employee::where('type', EmployeeType::NOT_REQULAR->value)
                ->whereNotIn('personal_number', $processedPersonalNumbers)
                ->delete();

            return response()->json(['message' => 'CSV file imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error importing CSV file: ' . $e->getMessage()], 500);
        }
    }
}
