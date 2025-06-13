<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\HospitalSeeder;
use Database\Seeders\DisorderHospitalSeeder;
use Database\Seeders\SpecialtyHospitalSeeder;
use Database\Seeders\ReviewSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        $this->call([
            HospitalSeeder::class,
            DisorderHospitalSeeder::class,
            SpecialtyHospitalSeeder::class,
            ReviewSeeder::class,
            HospitalDisorderSpecialtySeeder::class,
    ]);
    }
}
