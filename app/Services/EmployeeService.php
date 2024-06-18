<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class EmployeeService
{
    public function index(): Collection
    {
        $employees = Employee::withTrashed()
            ->get();

        return $employees;
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

    public function update(Request $request)
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
            $dbHeaders = array_map(function($header) use ($columnMapping) {
                return $columnMapping[$header] ?? null;
            }, $headers);
    
            $employeeDTOs = [];
            $csvPersonalIds = [];
    
            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                $employeeData = array_combine($dbHeaders, $data);
    
                $dto = new EmployeeDTO($employeeData);
    
                // Validate DTO
                // if (EmployeeValidator::validate($dto)) {
                    $employeeDTOs[] = $dto;
                    $csvPersonalIds[] = $dto->personal_id;
                // }
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
