<?php

namespace App\DTO;

use App\Enums\ClassificationName;
use App\Enums\Population;

class EmployeeDTO
{
    public $personal_id;
    public $personal_number;
    public $prefix;
    public $ranks;
    public $first_name;
    public $surname;
    public $department;
    public $branch;
    public $section;
    public $division;
    public $date_of_birth;
    public $security_class_start_date;
    public $age;
    public $classification;
    public $classification_name;
    public $phone_number;
    public $profession;
    public $gender;
    public $religion;
    public $country_of_birth;
    public $release_date;
    public $username;
    public $population;
    public $employee_id;

    public function __construct(array $data)
    {
        $this->personal_id = $data['personal_id'];
        $this->personal_number = $data['personal_number'];
        $this->ranks = $data['ranks'];
        $this->first_name = $data['first_name'];
        $this->surname = $data['surname'];
        $this->department = $data['department'] != "" ? $data['department'] : null;
        $this->branch = $data['branch'] != "" ? $data['branch'] : null;
        $this->section = $data['section'] != "" ? $data['section'] : null;
        $this->division = $data['division'];
        $this->date_of_birth = $data['date_of_birth'];
        $this->security_class_start_date = $data['security_class_start_date'];
        $this->age = $data['age'];
        $this->classification = $data['classification'];
        $this->phone_number = $data['phone_number'];
    }


    private function convertPersonalId(string $personalId): string
    {
        return str_pad($personalId, 9, '0', STR_PAD_LEFT);
    }


    private function convertName(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/[^\p{Hebrew}\s]/u', '', $name);

        return $name;
    }


    private function convertDate(string $date): ?string
    {
        if ($date) {
            $date = \DateTime::createFromFormat('d.m.Y', $date);
            return $date ? $date->format('Y-m-d') : null;
        }
        return null;
    }


    private function convertPhone(string $phone): ?string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 9 && $phone[0] !== '0') {
            $phone = '0' . $phone;
        }

        if (strlen($phone) != 10) {
            return null;
        }

        return substr($phone, 0, 3) . '-' . substr($phone, 3);
    }

    private function createUsername(): void
    {
        $this->username=  'army\\'.$this->prefix.$this->personal_number;
        dd($this->username);
    }
    public function convertDTO():void
    {
        $this->personal_id = $this->convertPersonalId($this->personal_id);
        // $this->prefix = Population::from($this->service_type)->getPrefix();
        $this->first_name = $this->convertName($this->first_name);
        $this->surname = $this->convertName($this->surname);
        $this->date_of_birth = $this->convertDate($this->date_of_birth);
        $this->security_class_start_date = $this->convertDate($this->security_class_start_date);
        $this->classification_name = ClassificationName::toHebrew($this->classification);
        $this->phone_number = $this->convertPhone($this->phone_number);
        $this->createUsername();

    }
}
