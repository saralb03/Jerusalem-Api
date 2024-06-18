<?php

namespace App\DTO;

class EmployeeDTO
{
    public $personal_id;
    public $personal_number;
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
    public $phone_number;

    public function __construct(array $data)
    {
        $this->personal_id = $data['personal_id'];
        $this->personal_number = $data['personal_number'];
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
        $this->phone_number = $data['phone_number'];
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
