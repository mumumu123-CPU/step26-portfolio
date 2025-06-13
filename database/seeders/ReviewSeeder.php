<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hospital::all()->each(function ($hospital) {
            Review::factory()
                ->count(5) // 各病院に5件のダミー
                ->create([
                    'hospital_id' => $hospital->id,
                ]);
        });
    }
}

