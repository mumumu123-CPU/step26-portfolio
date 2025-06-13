<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//use App\Models\Hospital;
use App\Models\Specialty;

class SpecialtyHospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    
     public function run(): void
    {

        //専門外来データを取得する。
        $specialties = json_decode(file_get_contents(storage_path('app/json/specialties.json')), true);
     
         foreach ($specialties as $name) {
             Specialty::firstOrCreate(['name' => $name]); //firstOrCreateは重複を防ぐ役割。
         }

         /*
        //　ランダムに専門外来を病院データと結びつける。
        $specialtyIds = Specialty::pluck('id')->toArray();

             foreach (Hospital::all() as $hospital) {
                 $randomSpecialties = collect($specialtyIds)->random(rand(1, 3));
                 $hospital->specialties()->sync($randomSpecialties);
            }
        */
    }
    
}

    /*
    public function run(): void
    {
        $hospitals = Hospital::all();

        foreach ($hospitals as $hospital) {
            $randomSpecialties = Specialty::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $hospital->specialties()->attach($randomSpecialties);
        }
    }
    */
