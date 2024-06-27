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

  
    public function update(string $filePath): Status | string
    {
        if (!file_exists($filePath)) {
            return Status::NOT_FOUND;
        }
        $fileContents = file($filePath);
        $headers = str_getcsv(array_shift($fileContents));
        $employeeDTOs = [];
        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            $employeeData = array_combine($headers, $data);
            $employeeDTO = new EmployeeDTO($employeeData);
            if (!EmployeeValidator::validate((array) $employeeDTO)) {
                continue;
            }
            $employeeDTO->convertDTO();
            $employeeDTOs[] = $employeeDTO;
        }
        DB::beginTransaction();
        try {
            foreach ($employeeDTOs as $dto) {
                $employee = Employee::updateOrCreate(
                    ['personal_number' => $dto->personal_number],
                    (array)$dto
                );
                $dto->employee_id = $employee->id;
                Details::updateOrCreate(
                    ['employee_id' => $dto->employee_id],
                    (array)$dto
                );
            }
            DB::commit();
            return Status::OK;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }


    public function import(string $file): Status|string
    {
        if (!$file) {
            return Status::NOT_FOUND;
        }
      
        try {
            $fileContents = file($file);
            dd($fileContents);
            $headers = str_getcsv(array_shift($fileContents));
            foreach ($fileContents as $line) {
                $data = str_getcsv($line);
                $rowData = array_combine($headers, $data);
                $employeeDTO = new EmployeeDTO($rowData);
                if (!EmployeeValidator::validate((array) $employeeDTO)) {
                    continue;
                }
                $employeeDTO->convertDTO();
                
                Employee::updateOrCreate(
                    ['personal_number' => $employeeDTO->personal_number],
                    (array) $employeeDTO
                );
            }
            return Status::OK;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
