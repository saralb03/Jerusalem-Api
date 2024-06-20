<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory()->count(5)->create();
        $adminUser = User::create(['personal_number' => 1111111]);
        $adminUser->assignRole('admin');
        $adminUser2 = User::create(['personal_number' => 2222222]);
        $adminUser2->assignRole('admin');
    }
}
