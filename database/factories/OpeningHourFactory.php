<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpeningHours>
 */
class OpeningHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Jsonファイルを読み込む
        $hoursList = json_decode(file_get_contents(storage_path('app/json/weekday_times.json')),true);

        //　ランダムに１件選ぶ。
        $random = collect($hoursList)->random();
        return [
            'weekday_times' => json_encode($random),
                    ];
    }
}
