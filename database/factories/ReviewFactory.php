<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
     public function definition(): array
     {
         
        $positiveComments = json_decode(file_get_contents(storage_path('app/json/positive_reviews.json')), true);
        $negativeComments = json_decode(file_get_contents(storage_path('app/json/negative_reviews.json')), true);

        // ポジ・ネガどちらかランダム（60%でポジ）
        $sentiment = $this->faker->boolean(60) ? 'positive' : 'negative';

        // 文字数カテゴリの決定
        $lengthCategory = collect([
            'short' => [10, 40],
            'medium' => [41, 100],
            'long' => [101, 300],
        ])->keys()->random();

        // 該当カテゴリからランダムに選択
        $commentList = collect(
            $sentiment === 'positive' ? $positiveComments[$lengthCategory] : $negativeComments[$lengthCategory]
        );
        $baseComment = $commentList->random();
        return [
            'hospital_id' => rand(1, 50), // 病院ID（50個作ってるならOK）
            'comment' => $baseComment,
            'rating' => $this->faker->numberBetween(1, 5), // 1〜5の評価。問題がある。コメントの評価は高いのにレートが低い、あるいは否定的なコメントなのに、レートは高評価。解決法→jsonファイルにコメントと一緒に連想配列でレートものせる。
        ];
        
        
     }
        
}
