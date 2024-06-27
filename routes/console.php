<?php

use App\Services\EmployeeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;


Schedule::call(function () {
    try {
        $employeeService = new EmployeeService();
        $employeeService->update(env('DAILY_FILE_ADDRESS'), true);
        Log::info('Employees updated successfully.');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
})->daily()->at('08:00');
