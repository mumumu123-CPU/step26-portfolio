<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//use App\Models\Hospital;
use App\Models\Disorder;

class DisorderHospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    //疾患データの作成
    
    public function  run(): void 
    {
        $disorders = json_decode(file_get_contents(storage_path('app/json/disorders.json')), true );

        foreach ($disorders as $name) {
            Disorder::firstOrCreate(['name' => $name]);
        }


        /*
        //各病院に１〜３この疾患をランダムで紐づける
        $disorderIds = Disorder::pluck('id')->toArray();

        foreach (Hospital::all() as $hospital) {
            $randomDisorders = collect($disorderIds)->random(rand(1, 5));
            $hospital->disorders()->sync($randomDisorders);
        }
        */
    }
    
      

     /*
    public function run(): void
    {
        $hospital = Hospital::all();
        foreach ($hospital as $hospital) {
            $randomDisorders = Disorder::inRandomOrder()->limit(rand(1,3))->pluck('id');
            $hospital->disorders()->attach($randomDisorders);
        }
    }
    */
}
