<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Division::create(['name' => 'אט"ל 700', 'invalid_name' => 'אטל 700']);
        Division::create(['name' => 'בסיס 128 לשכות', 'invalid_name' => 'בסיס 128 לשכות']);
        Division::create(['name' => 'חט"ל 99', 'invalid_name' => 'חטל 99']);
        Division::create(['name' => 'יח\' חי"ח 5050', 'invalid_name' => 'יח\' חיח 5050']);
        Division::create(['name' => 'יח\' מטמו"ן 5000', 'invalid_name' => 'יח\' מטמון 5000']);
        Division::create(['name' => 'יפת"ח 5095', 'invalid_name' => 'יפתח 5095']);
        Division::create(['name' => 'מז"י 23', 'invalid_name' => 'מזי 23']);
        Division::create(['name' => 'מנהלת הרק"ם 8510', 'invalid_name' => 'מנהלת הרקם 8510']);
        Division::create(['name' => 'מקטנא"ר 58', 'invalid_name' => 'מקטנאר 58']);
        Division::create(['name' => 'מקרפ"ר 57', 'invalid_name' => 'מקרפר 57']);
        Division::create(['name' => 'מרכז תע"צ 9508', 'invalid_name' => 'מרכז תעצ 9508']);
        Division::create(['name' => 'משר"פ 8280', 'invalid_name' => 'משרפ 8280']);
    }
}
