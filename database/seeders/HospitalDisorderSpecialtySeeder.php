<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\Disorder;
use App\Models\Specialty;

class HospitalDisorderSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disorderToSpecialty = [
            'うつ病' => 'うつ病外来',
            '双極性障害（躁うつ病）' => '双極性障害外来',
            '統合失調症' => '統合失調症専門外来',
            'パニック障害' => 'パニック障害外来',
            '社交不安障害（SAD）' => '不安障害外来',
            '全般性不安障害（GAD）' => '不安障害外来',
            '強迫性障害（OCD）' => '強迫性障害（OCD）外来',
            '注意欠如・多動症（ADHD）' => '発達障害外来（ADHD/ASD）',
            '自閉スペクトラム症（ASD）' => '発達障害外来（ADHD/ASD）',
            '摂食障害（過食症・拒食症）' => '摂食障害外来',
            '睡眠障害（不眠症など）' => '睡眠障害外来',
            'アルコール依存症' => 'アルコール依存症外来',
            'ギャンブル依存症' => 'ギャンブル依存症外来',
            '薬物依存症' => null,
            '適応障害' => 'ストレス関連障害外来',
            'PTSD（心的外傷後ストレス障害）' => 'トラウマ/PTSD外来',
            '境界性パーソナリティ障害（BPD）' => '思春期外来',
            '解離性障害' => 'ストレス関連障害外来',
            '妄想性障害' => '統合失調症専門外来',
            'てんかん' => null,
            '軽度認知障害（MCI）' => '認知症外来',
            '認知症（アルツハイマー型など）' => '認知症外来',
            '統合失調型パーソナリティ障害' => '統合失調症専門外来',
            '季節性情動障害（SAD）' => 'うつ病外来',
            '月経前不快気分障害（PMDD）' => '女性メンタル外来',
            'チック・トゥレット症候群' => '児童精神科外来',
            '学習障害（LD）' => '児童精神科外来',
            '発達性協調運動障害（DCD）' => '児童精神科外来',
            '産後うつ' => '産後メンタル外来',
        ];

    $disorderMap = Disorder::pluck('id', 'name')->toArray();
    $specialtyMap = Specialty::pluck('id', 'name')->toArray();

    foreach (Hospital::all() as $hospital) {
        // ランダムに3〜5件のDisorderを病院に割り当て
        $randomDisorders = Disorder::inRandomOrder()->limit(rand(3, 5))->pluck('id');
        $hospital->disorders()->sync($randomDisorders);

        // 紐づけたDisorder名を取得
        $disorderNames = Disorder::whereIn('id', $randomDisorders)->pluck('name');

        // 対応するSpecialty名をマッピングから取得
        $matchedSpecialties = [];

        foreach ($disorderNames as $name) {
            if (isset($disorderToSpecialty[$name]) && $disorderToSpecialty[$name]) {
                $matchedSpecialties[] = $disorderToSpecialty[$name];
            }
        }

        // Specialty名 → ID変換
        $matchedSpecialtyIds = array_map(fn($name) => $specialtyMap[$name] ?? null, $matchedSpecialties);
        $matchedSpecialtyIds = array_filter($matchedSpecialtyIds); // null除去

        // ランダム追加Specialty（0〜2件）
        $extraSpecialtyIds = Specialty::inRandomOrder()->limit(rand(0, 2))->pluck('id')->toArray();

        // 重複除去してmerge
        $finalSpecialtyIds = collect($matchedSpecialtyIds)->merge($extraSpecialtyIds)->unique()->toArray();

        $hospital->specialties()->sync($finalSpecialtyIds);
    }

    }
}
