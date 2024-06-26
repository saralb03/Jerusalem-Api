<?php
namespace App\DTO;
use App\Enums\ClassificationName;
use App\Enums\Population;
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
        $this->personal_id = $data['תז'] ?? $data['ת"ז'];
        $this->personal_number = $data['מספר אישי'];
        $this->first_name = $data['שם פרטי'];
        $this->surname = $data['שם משפחה'];
        $this->population = $data['סוג שרות'] ?? $data['סוג שירות'];
        $this->rank = $data['דרגה'];
        $this->department = $data['מחלקה'] ?? null;
        $this->branch = $data['ענף'] ?? null;
        $this->section = $data['מדור'] ?? null;
        $this->division = $data['יחידת רישום'];
        $this->date_of_birth = $data['תאריך לידה'];
        $this->security_class_start_date = $data['תאריך מתן סיווג נוכחי'] ?? null;
        $this->age = $data['גיל'] ?? null;
        $this->classification = $data['סב"ט נוכחי'] ?? null;
        $this->phone_number = $data['טלפון'] ?? $data['קידומת מספר טלפון'] . $data['מספר טלפון'];
        $this->profession = $data['מקצוע'] ?? null;
        $this->gender = $data['מין'] ?? null;
        $this->religion = $data['דת'] ?? null;
        $this->country_of_birth = $data['ארץ לידה'] ?? null;
        $this->release_date = $data['תאריך שחרור'];
    }
    private function convertPersonalId(string $personalId): string
    {
        return str_pad($personalId, 11, '0', STR_PAD_LEFT);
    }
    private function convertPersonalNumber(string $personalNumber): string
    {
        $length = strlen($personalNumber);
        if ($length == 9 || $length == 11) {
            return substr($personalNumber, $length - 9, 7);
        }
        return $personalNumber;
    }
    private function convertName(string $name): string
    {
        $name = str_replace(['-', '_'], ' ', $name);
        $name = preg_replace('/[^\p{Hebrew}\s]/u', '', $name);
        return $name;
    }
    private function convertRank(string $rank): string
    {
        return preg_replace('/[^\p{Hebrew}\s]/u', '', $rank);
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
        $this->user_name =  'army\\' . $this->prefix . $this->personal_number;
        dd($this->user_name);
    }
    public function convertDTO(): void
    {
        $this->personal_id = $this->convertPersonalId($this->personal_id);
        $this->personal_number = $this->convertPersonalNumber($this->personal_number);
        $this->prefix = Population::from($this->population)->getPrefix();
        $this->rank = $this->convertRank($this->rank);
        $this->first_name = $this->convertName($this->first_name);
        $this->surname = $this->convertName($this->surname);
        $this->date_of_birth = $this->convertDate($this->date_of_birth);
        $this->security_class_start_date = $this->convertDate($this->security_class_start_date);
        $this->classification_name = ClassificationName::toHebrew($this->classification);
        $this->phone_number = $this->convertPhone($this->phone_number);
        $this->createUsername();
    }
}