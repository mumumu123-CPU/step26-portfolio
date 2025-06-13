<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hospital>
 */
class HospitalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // type（hospital / clinic）について
        // Factoryで名前の付け方を分けるために導入
        // 病院: 「〇〇病院」「〇〇医療センター」
        // クリニック: 「〇〇メンタルクリニック」「〇〇心療内科」
        // ただしFactoryだけだと内容との整合性は取れない
        // →　例：通院しかできないのに「病院」になる等
        // そのためtypeを明示することで、以下が可能
        // - 名前の付け方コントロール
        // - 入院/通院の判定に活用
        // 今後に活用方法
        // - 検索時のフィルタ条件に使用（例：「病院のみ」表示
        // - ポートフォリオ上で病院/クリニックの違いを明示できる
        
        // hospitalは40％の確率で生成
        $type = $this->faker->boolean(40) ? 'hospital' : 'clinic';
        
        //　fakerで作成したもの「市」を取り除く処理。str_replaceの第１引数に変換したい文字を指定。第２引数に変換後の内容指定。第３引数には検索したい文字列を指定。
        $cityName = str_replace('市', '', $this->faker->city);

        // typeがhospitalであれば、「病院」か「医療センタ」をつける。そうでなければ、「メンタルクリニック」等をつける。
        $name = $type === 'hospital'
            ? $cityName . collect(['病院', '医療センター'])->random()
            : $cityName . collect(['メンタルクリニック', '心療内科', 'こころのクリニック'])->random();

        // 各JSONデータの読み込み。trueなので連想配列として保存。json_decodeでjosonファイルをphpの配列として使用できるように加工（file_get_contesntsでjsonの中身を文字列に変換。josn_decodeのtrueでphpの配列に変換。jsonファイルを読み込むため、storage_pathでstorageディレクトリの中のファイルを指定。）
        $prefectures = json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true);
        $dayOfWeeks = json_decode(file_get_contents(storage_path('app/json/day_of_week.json')), true);
        $amOpens = json_decode(file_get_contents(storage_path("app/json/am_open_{$type}.json")), true);
        $pmOpens = json_decode(file_get_contents(storage_path("app/json/pm_open_{$type}.json")), true);
        $features = json_decode(file_get_contents(storage_path('app/json/features.json')), true);
        $treatments = json_decode(file_get_contents(storage_path('app/json/treatments.json')), true);


        // 配列操作を簡単にするためにコレクション化　
        $amSource = collect($amOpens);
        $pmSource = collect($pmOpens);


        // 読み込んだ医療機関の営業時間の先頭や末尾の空白を取り除き、１つだけランダム化して$amや$pmに代入。trimが取り除く機能
        $am = trim($amSource->random());
        $pm = trim($pmSource->random());

        // $typeがclinicであれば、
        if ($type === 'clinic') {
            //  $amSourceに午前診療なしが入ってったら、排除して新しい配列を作成する。
            $validAm = $amSource->reject(fn($a) => str_contains($a, '午前診療なし'))->values();
            //  $pmSourceに午後診療なしが入ってったら、排除して新しい配列を作成する。
            $validPm = $pmSource->reject(fn($p) => str_contains($p, '午後診療なし'))->values();
            
            // $amに午前診療なしがある場合、$am_openは空白にする。$pm_openは$validPmの中身が空っぽでなければ、ランダムで１つ入れる。空なら、'13:00〜16:00'を入れる。
            if (str_contains($am, '午前診療なし')) {
                $am_open = '';
                $pm_open = $validPm->isNotEmpty() ? $validPm->random() : '13:00〜16:00';
            } elseif (str_contains($pm, '午後診療なし')) {
                $pm_open = '';
                $am_open = $validAm->isNotEmpty() ? $validAm->random() : '09:00〜12:00';
            } else {
                $am_open = $am;
                $pm_open = $pm;
            }
            //　午前診療なし、午後診療なし、どちらもない場合は、コレクション化とtrimしランダムかしたものを代入する
        } else {
            $am_open = $am;
            $pm_open = $pm;
        }

       




        // 特徴・治療法は type に合うものだけ抽出。filterにかけてtypeが3か。$typeがclinicである場合は1を。そうでない場合は2を取得する。
        $validFeatures = collect($features)->filter(fn($f) => $f['type'] === 3 || $f['type'] === ($type === 'clinic' ? 1 : 2));
        $validTreatments = collect($treatments)->filter(fn($t) => $t['type'] === 3 || $t['type'] === ($type === 'clinic' ? 1 : 2));

        //　写経
        // $validFeatures = collect($features)->filter(fn($f) => $f['type'] === 3 || $['type'] === ($type === 'clinic' ? 1 : 2));

        // 精神科救急なら完全予約制は除外
        $finalFeatures = $validFeatures->random(rand(1, 3))->pluck('name')->toArray();
        if (in_array('精神科救急対応', $finalFeatures)) {
            $finalFeatures = array_filter($finalFeatures, fn($f) => $f !== '完全予約制');
        }

        // 病院には必ず「通院・入院」、クリニックには「通院」
        $defaultTreatment = $type === 'clinic' ? ['通院'] : ['通院', '入院'];
        $treatmentOptions = collect($validTreatments)->pluck('name')->diff($defaultTreatment)->random(rand(1, 4))->toArray();
        $pref = collect($prefectures)->random(); 


        return [
            'name' => $name, 
            'address' => $pref . $this->faker->city . $this->faker->streetAddress,
            'type' => $type,
            'homepage_url' => $this->faker->url,
            'map_url' => $this->faker->url,
            'prefecture' => $pref,
            'station' => str_replace('市', '', $this->faker->city) . '駅',
            'day_of_week' => collect($dayOfWeeks)->random(),
            'am_open' => $am_open,
            'pm_open' => $pm_open,
            'feature' => implode(', ', $finalFeatures),
            'treatment' => implode(', ', array_merge($defaultTreatment, $treatmentOptions)),
            'phone' => $this->faker->phoneNumber,
        ];

        
    }

}
