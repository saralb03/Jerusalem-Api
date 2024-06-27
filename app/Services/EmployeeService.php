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

class EmployeeService
{
    public function index(array $requestedColumns): Collection
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
        return $employees;
    }


    public function update(string $filePath, bool $createDetails): Status | string
    {
        if (!file_exists($filePath)) {
            return Status::NOT_FOUND;
        }

        try {
            $fileContents = file($filePath);
            $headers = str_getcsv(array_shift($fileContents));

            DB::beginTransaction();

            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                $employeeData = array_combine($headers, $data);
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
            return $e->getMessage();
        }
    }
}
