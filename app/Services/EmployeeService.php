<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Validators\EmployeeValidator;
class EmployeeService
{
    public function index(array $requestedColumns)
    {
        // Retrieve requested columns from query parameter, default to all if not specified
        // $requestedColumns = $request->query('columns') ? explode(',', $request->query('columns')) : null;

        // Convert camelCase column names to snake_case
        if ($requestedColumns) {
            $requestedColumns = array_map(function ($column) {
                return Str::snake($column);// Use snake_case() to convert camelCase to snake_case
            }, $requestedColumns);
        }

        // Fetch employees with requested columns or all columns if none specified
        $employees = $requestedColumns ?
            Employee::select($requestedColumns)->get() :
            Employee::all();

        return response()->json($employees);
    }

    public function updateData()
    {
        $employees = Employee::query()
            ->get();

        foreach ($employees as $employee) {
            $employee->update([
                'updated_at' => Carbon::now()
            ]);
        }
    }

    public function update()
    {
        $filePath = "C:\\Users\\Emet-Dev\\Documents\\New folder\\employees.csv";
        // Column mapping from CSV to database
        $columnMapping = [
            'תז' => 'personal_id',
            'מספר אישי' => 'personal_number',
            'דרגה' => 'ranks',
            'שם משפחה' => 'surname',
            'שם פרטי' => 'first_name',
            'מחלקה' => 'department',
            'יחידה' => 'division',
            'סוג שרות' => 'service_type',
            'תאריך לידה' => 'date_of_birth',
            'קוד סוג ש' => 'service_type_code',
            'תאריך תחילת סוש' => 'security_class_start_date',
            'תאריך תחילת שרות' => 'service_start_date',
            'סוג חייל' => 'solider_type',
            'גיל' => 'age',
            'סיווג' => 'classification',
            'טלפון' => 'phone_number',
        ];

        if (file_exists($filePath)) {
            $fileContents = file($filePath);

            $headers = str_getcsv(array_shift($fileContents));
            $dbHeaders = array_map(function ($header) use ($columnMapping) {
                return $columnMapping[$header] ?? null;
            }, $headers);

            $employeeDTOs = [];
            $csvPersonalIds = [];

            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                $employeeData = array_combine($dbHeaders, $data);

                if (!EmployeeValidator::validate($employeeData)) {
                    continue;
                }

                $employeeDTO = new EmployeeDTO($employeeData);

                $employeeDTOs[] = $employeeDTO;
                $csvPersonalIds[] = $employeeDTO->personal_id;
            }

            DB::beginTransaction();
            try {
                // Soft delete employees not in the CSV
                Employee::whereNotIn('personal_id', $csvPersonalIds)->delete();

                // Save all valid DTOs to the database using updateOrCreate
                foreach ($employeeDTOs as $dto) {
                    $employee = Employee::withTrashed()->updateOrCreate(
                        ['personal_id' => $dto->personal_id],
                        (array)$dto
                    );

                    // Restore if it was soft deleted
                    if ($employee->trashed()) {
                        $employee->restore();
                    }
                }

                // Commit the transaction
                DB::commit();

                return redirect()->back()->with('success', 'CSV file imported successfully.');
            } catch (\Exception $e) {
                // Rollback the transaction on error
                DB::rollBack();

                return redirect()->back()->with('error', 'Error importing CSV file: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File not found.');
    }
}
