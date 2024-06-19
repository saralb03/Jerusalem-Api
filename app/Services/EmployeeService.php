<?php

namespace App\Services;

use App\DTO\EmployeeDTO;
use App\Models\Details;
use App\Models\Employee;
use App\Validators\EmployeeValidator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeService
{
    public function index(array $requestedColumns)
    {
        if ($requestedColumns) {
            $requestedColumns = array_map(function ($column) {
                return Str::snake($column);
            }, $requestedColumns);
        }

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
        $filePath = "C:\\Users\\Emet-Dev\\Documents\\New folder\\employees+1.csv";
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
                $csvPersonalNumbers[] = $employeeData["personal_number"];
            }

            DB::beginTransaction();
            try {
                $employees = Employee::whereNotIn('personal_number', $csvPersonalNumbers)->get();
                foreach ($employees as $employee) {
                    $employee->details()->delete();
                    $employee->delete();
                }


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

                    if ($employee->trashed()) {
                        $employee->restore();
                        $details->restore();
                    }
                }

                DB::commit();

                return redirect()->back()->with('success', 'CSV file imported successfully.');
            } catch (\Exception $e) {
                DB::rollBack();

                return redirect()->back()->with('error', 'Error importing CSV file: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    public function import(Request $request)
{
    // Validate the request to ensure a file is uploaded
    $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048', // Adjust max file size as needed
    ]);

    // Retrieve the uploaded file from the request
    $file = $request->file('file');

    // Check if file upload was successful
    if ($file) {
        try {
            // Read file contents into an array of lines
            $fileContents = file($file->getPathname());

            // Process each line in the CSV file
            foreach ($fileContents as $line) {
                // Parse CSV line into an array of data
                $data = str_getcsv($line);

                // Update or create Employee based on personal_number
                $employee = Employee::withTrashed()->updateOrCreate([
                    'personal_number' => $data["personal_number"],
                ], [
                    'user_name' => $data["user_name"], // Update user_name if record exists
                    // Add other fields to update or create as needed
                ]);

                // Restore the employee if it was soft deleted
                if ($employee->trashed()) {
                    $employee->restore();
                }
            }

            return redirect()->back()->with('success', 'CSV file imported successfully.');
        } catch (\Exception $e) {
            // Handle exceptions (e.g., file reading errors, database errors)
            return redirect()->back()->with('error', 'Error importing CSV file: ' . $e->getMessage());
        }
    }

    // Handle case where no file was uploaded (though should be covered by validation)
    return redirect()->back()->with('error', 'No file uploaded.');
}

}
