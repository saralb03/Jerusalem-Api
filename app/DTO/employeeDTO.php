<?php

namespace App\DTO;

use App\Enums\ServiceType;

class EmployeeDTO
{
    public $personal_id;
    public $personal_number;
    public $prefix;
    public $ranks;
    public $surname;
    public $first_name;
    public $department;
    public $branch;
    public $section;
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

    private $divisions = [
        ['אט"ל 700', 'אטל 700'], ['בסיס 128 לשכות', 'בסיס 128 לשכות'],
        ['חט"ל 99', 'חטל 99'], ['יח\' חי"ח 5050', 'יח\' חיח 5050'], ['יח\' מטמו"ן 5000', 'יח\' מטמון 5000'],
        ['יפת"ח 5095', 'יפתח 5095'], ['מז"י 23', 'מזי 23'],
        ['מנהלת הרק"ם 8510', 'מנהלת הרקם 8510'], ['מקטנא"ר 58', 'מקטנאר 58'],
        ['מקרפ"ר 57', 'מקרפר 57'], ['מרכז תע"צ 9508', 'מרכז תעצ 9508'],
        ['משר"פ 8280', 'משרפ 8280'],
    ];

    public function __construct(array $data)
    {
        $classification_names = [
            1 => 'שו"ס',
            2 => 'סודי ביותר קהילה',
            3 => 'סודי ביותר',
            4 => 'סודי',
            5 => 'שמור',
        ];

        match ($data['service_type']) {
            ServiceType::MISSION_CIVILAN->value => $prefix = 'C',
            ServiceType::DUTY->value, ServiceType::DUTY_REGULARITY->value,
            ServiceType::DISCHARGE->value, ServiceType::REGULARITY->value => $prefix = 'S',
            ServiceType::RESERVES, ServiceType::VOLUNTERR_RESERVES->value => $prefix = 'M',
            default => $prefix = '',
        };

        $this->personal_id = $this->convertPersonalId($data['personal_id']);
        $this->personal_number = $data['personal_number'];
        $this->prefix = $prefix;
        $this->ranks = $data['ranks'];
        $this->surname = $this->convertName($data['surname']);
        $this->first_name = $this->convertName($data['first_name']);
        $this->department = $data['department'] ?? "";
        $this->branch = $data['branch'] ?? "";
        $this->section = $data['section'] ?? "";
        $this->division = $this->validateAndCorrectDivision($data['division']);
        $this->service_type = $data['service_type'];
        $this->date_of_birth = $this->convertDate($data['date_of_birth']);
        $this->service_type_code = $data['service_type_code'];
        $this->security_class_start_date = $this->convertDate($data['security_class_start_date']);
        $this->service_start_date = $this->convertDate($data['service_start_date']);
        $this->solider_type = $data['solider_type'];
        $this->age = $data['age'];
        $this->classification = $data['classification'];
        $this->classification_name = $classification_names[$data['classification']];
        $this->population_id = $data['population_id'];
        $this->phone_number = $this->convertPhone($data['phone_number']);
        $this->user_name = $data['user_name'];
    }

    private function convertDate(string $date) :string | null
    {
        if ($date) {
            $date = \DateTime::createFromFormat('d.m.Y', $date);
            return $date ? $date->format('Y-m-d') : null;
        }
        return null;
    }

    private function convertPhone(string $phone):string|null
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) != 10) {
            return null;
        }

        $formattedPhone = substr($phone, 0, 3) . '-' . substr($phone, 3);
        return $formattedPhone;
    }

    private function convertPersonalId(string $personalId):string
    {
        while (strlen($personalId) < 9) {
            $personalId = "0" . $personalId;
        }
        return $personalId;
    }

    private function convertName(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/[^a-zA-Z\s]/', '', $name);

        return $name;
    }

    private function validateAndCorrectDivision(string $division): string|null
    {
        foreach ($this->divisions as $div) {
            if ($division === $div[1] || $division === $div[0]) {
                return $div[0];
            }
        }
        return null;
    }
}