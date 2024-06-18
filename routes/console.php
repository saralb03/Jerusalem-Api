<?php

use App\Services\EmployeeService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;


Schedule::call(function () {
    try {
        $employeeService = new EmployeeService();
        $employeeService->update();
        Log::info('Employees updated successfully.');
    } catch (\Exception $e) {
        Log::error($e->getMessage());
    }
})->daily()->at('08:00');
