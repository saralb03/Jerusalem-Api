<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Models\Details;
use App\Models\Employee;
use App\Validators\DetailsValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeService
{
    public function index(array $requestedColumns)
    {
        // Retrieve requested columns from query parameter, default to all if not specified
        // $requestedColumns = $request->query('columns') ? explode(',', $request->query('columns')) : null;

        // Convert camelCase column names to snake_case
        if ($requestedColumns) {
            $requestedColumns = array_map(function ($column) {
                return Str::snake($column); // Use snake_case() to convert camelCase to snake_case
            }, $requestedColumns);
        }

        // Fetch employees with requested columns or all columns if none specified
        $employees = $requestedColumns ?
            Employee::select($requestedColumns)->get() :
            Employee::all();

        return response()->json($employees);
    }

    // public function updateData()
    // {
    //     $employees = Employee::query()
    //         ->get();

    //     foreach ($employees as $employee) {
    //         $employee->update([
    //             'updated_at' => Carbon::now()
    //         ]);
    //     }
    // }

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
                
                if (!DetailsValidator::validate($employeeData)) {
                    continue;
                }
                
                $employeeDTO = new EmployeeDTO($employeeData);
                
                $employeeDTOs[] = $employeeDTO;
                $csvPersonalIds[] = $employeeDTO->personal_id;
                // $csvPersonalNumbers[] = $employeeData["personal_number"];
            }
            
            DB::beginTransaction();
            try {
                // Soft delete employees not in the CSV
                // Employee::whereNotIn('personal_number', $csvPersonalNumbers)->delete();
                Details::whereNotIn('personal_id', $csvPersonalIds)->delete();
                
                
                // Save all valid DTOs to the database using updateOrCreate
                foreach ($employeeDTOs as $dto) {
                    // dd($dto);
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
