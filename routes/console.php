<?php

use App\Services\EmployeeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;


Schedule::call(function () {
    try {
        $employeeService = new EmployeeService();
        $employeeService->update("C:\\Users\\Emet-Dev\\Documents\\New folder\\employees-2.csv");
        Log::info('Employees updated successfully.');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
})->daily()->at('08:00');
