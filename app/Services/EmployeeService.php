<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Models\Details;
use App\Models\Employee;
use App\Validators\EmployeeValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeService
{
    public function index(array $requestedColumns)
    {
        // Convert camelCase column names to snake_case
        if ($requestedColumns) {
            $requestedColumns = array_map(function ($column) {
                return Str::snake($column); // Use snake_case() to convert camelCase to snake_case
            }, $requestedColumns);
        }

        // Fetch employees with requested columns or all columns if none specified
        $employees = $requestedColumns
            ? Employee::leftJoin('details', 'employees.id', '=', 'details.employee_id')
            ->select($requestedColumns)
            ->get()
            : Employee::leftJoin('details', 'employees.id', '=', 'details.employee_id')
            ->get();

        return response()->json($employees);
    }

    public function update()
    {
        $filePath = "C:\\Users\\Emet-Dev-23\\Desktop\\Projects\\employees.csv";
        // Column mapping from CSV to database
        $columnMapping = [
            'תז' => 'personal_id',
            'מספר אישי' => 'personal_number',
            'דרגה' => 'ranks',
            'שם משפחה' => 'surname',
            'שם פרטי' => 'first_name',
            'שם משתמש' => 'user_name',
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
            'מזהה אוכלוסיה' => 'population_id',
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
                Details::whereNotIn('personal_id', $csvPersonalIds)->delete();
                
                foreach ($employeeDTOs as $dto) {
                    $employee = Employee::withTrashed()->updateOrCreate(
                        ['personal_number' => $dto->personal_number],
                        (array)$dto
                    );
                    
                    $dto->employee_id = $employee->id;
                    
                    $details = Details::withTrashed()->updateOrCreate(
                        ['personal_id' => $dto->personal_id],
                        (array)$dto
                    );
                    
                    // Restore if it was soft deleted
                    if ($employee->trashed()) {
                        $employee->restore();
                        $details->restore();
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
