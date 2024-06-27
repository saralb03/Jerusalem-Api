<?php

namespace App\DTO;

use App\Enums\ClassificationName;
use App\Enums\Population;
use App\Enums\Rank;
use App\Enums\Religion;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;

class EmployeeDTO
{
    public $personal_id;
    public $personal_number;
    public $first_name;
    public $surname;
    public $user_name;
    public $population;
    public $prefix;
    public $rank;
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
    public $employee_id;

    public function __construct(array $data)
    {
        $this->personal_id = $data['personal_id'];
        $this->personal_number = $data['personal_number'];
        $this->first_name = $data['first_name'];
        $this->surname = $data['surname'];
        $this->population = $data['population'] ?? null;
        $this->rank = $data['rank'] ?? null;
        $this->department = $data['department'] ?? null;
        $this->branch = $data['branch'] ?? null;
        $this->section = $data['section'] ?? null;
        $this->division = $data['division'] ?? null;
        $this->date_of_birth = $data['date_of_birth'] ?? null;
        $this->security_class_start_date = $data['security_class_start_date'] ?? null;
        $this->age = $data['age'] ?? null;
        $this->classification = $data['classification'] ?? null;
        $this->phone_number = $data['phone_number'] ??
            ($data['prefix_phone'] ?? '' && $data['suffix_phone'] ?? '' ?
                $data['prefix_phone'] . $data['suffix_phone'] : null);
        $this->profession = $data['profession'] ?? null;
        $this->gender = $data['gender'] ?? null;
        $this->religion = $data['religion'] ?? null;
        $this->country_of_birth = $data['country_of_birth'] ?? null;
        $this->release_date = $data['release_date'] ?? null;
        $this->user_name = $data['user_name'] ?? null;
    }

    private function convertPersonalNumber(): void
    {
        $length = strlen($this->personal_number);
        if ($length == 9 || $length == 11) {
            $this->personal_number =  substr($this->personal_number, $length - 9, 7);
        }
    }

    private function convertName(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        return preg_replace('/[^\p{Hebrew}\s]/u', '', $name);
    }


    private function convertDate(?string $date): ?string
    {
        if (!$date) return null;

        $date = \DateTime::createFromFormat('d.m.Y', $date);
        return $date ? $date->format('Y-m-d') : null;
    }


    private function convertPhone(): void
    {
        if (!$this->phone_number) return;

        $this->phone_number = preg_replace('/[^0-9]/', '', $this->phone_number);
        
        if (strlen($this->phone_number) == 9 && $this->phone_number[0] !== '0') {
            $this->phone_number = '0' . $this->phone_number;
        } else if (strlen($this->phone_number) != 10) {
            $this->phone_number = null;
        }

        if (!$this->phone_number) return;
        $this->phone_number = substr($this->phone_number, 0, 3) . '-' . substr($this->phone_number, 3);
    }


    public function convertDTO(): void
    {
        $this->personal_id = str_pad($this->personal_id, 9, '0', STR_PAD_LEFT);
        $this->convertPersonalNumber();
        $this->population = Population::getValid($this->population);
        $this->prefix = Population::from($this->population)->getPrefix();
        $this->rank = Rank::fromHebrew($this->rank);
        $this->first_name = $this->convertName($this->first_name);
        $this->surname = $this->convertName($this->surname);
        $this->date_of_birth = $this->convertDate($this->date_of_birth);
        $this->security_class_start_date = $this->convertDate($this->security_class_start_date);
        $this->classification_name = ClassificationName::toHebrew($this->classification);
        $this->convertPhone();
        $religion = Religion::tryFrom($this->religion);
        $this->religion = $religion ? $religion->value : null;
        $this->release_date = $this->convertDate($this->release_date);
        $this->user_name =  $this->user_name ? $this->user_name : 'army\\' . $this->prefix . $this->personal_number;
    }
}
