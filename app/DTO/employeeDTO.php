<?php

namespace App\DTO;

USE App\Enums\ServiceType;

class EmployeeDTO
{
    public $personal_id;
    public $personal_number;
    public $prefix;
    public $ranks;
    public $surname;
    public $first_name;
    public $department;
    public $division;
    public $service_type;
    public $date_of_birth;
    public $service_type_code;
    public $security_class_start_date;
    public $service_start_date;
    public $solider_type;
    public $age;
    public $classification;
    public $classification_name;
    public $phone_number;
    public $population_id;
    public $employee_id; 
    public $user_name;

    public function __construct(array $data)
    {

        match ($data['service_type']) {
            ServiceType::MISSION_CIVILAN->value => $prefix = 'C',
            ServiceType::DUTY->value, ServiceType::DUTY_REGULARITY->value, 
            ServiceType::DISCHARGE->value, ServiceType::REGULARITY->value => $prefix = 'S',
            ServiceType::RESERVES, ServiceType::VOLUNTERR_RESERVES->value => $prefix = 'M',
            default => $prefix = '',
        };

        $this->personal_id = $data['personal_id'];
        $this->personal_number = $data['personal_number'];
        $this->prefix = $prefix;
        $this->ranks = $data['ranks'];
        $this->surname = $data['surname'];
        $this->first_name = $data['first_name'];
        $this->department = $data['department'];
        $this->division = $data['division'];
        $this->service_type = $data['service_type'];
        $this->date_of_birth = $this->convertDate($data['date_of_birth']);
        $this->service_type_code = $data['service_type_code'];
        $this->security_class_start_date = $this->convertDate($data['security_class_start_date']);
        $this->service_start_date = $this->convertDate($data['service_start_date']);
        $this->solider_type = $data['solider_type'];
        $this->age = $data['age'];
        $this->classification = $data['classification'];
        $this->classification_name = "hi";
        $this->population_id = $data['population_id'];
        $this->phone_number = $data['phone_number'];
        $this->user_name = $data['user_name'];
    }

    private function convertDate($date)
    {
        if ($date) {
            $date = \DateTime::createFromFormat('d.m.Y', $date);
            return $date ? $date->format('Y-m-d') : null;
        }
        return null;
    }
}
