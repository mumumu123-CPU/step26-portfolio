<?php

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\OpeningHour;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OpeningHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 病院一覧を取得して、１件ずつ営業時間（診療時間）と紐づける
        Hospital::all()->each(function($hospital) {
            $hospital->openingHours()->create(
                OpeningHour::factory()->make()->toArray());
        });
    }
}
