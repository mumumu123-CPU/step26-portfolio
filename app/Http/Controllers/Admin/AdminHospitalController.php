<?php

namespace App\Http\Controllers\Admin;

use App\Models\Hospital;
use App\Models\Disorder;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // ← これ抜けてること多いので注意！

class AdminHospitalController extends Controller
{
    //　管理者ログイン
    public function login() {
        
        return view('admin.login');
    }
    //　管理者用の病院一覧を表示
    public function index(Request $request)
    {
        $query = Hospital::with(['reviews', 'disorders', 'specialties']);

        if ($request->filled('prefecture')) {
            $query->where('prefecture', $request->input('prefecture'));
        }

        if ($request->filled('disorder_id')) {
            $query->whereHas('disorders', function ($q) use ($request) {
                $q->where('disorders.id', $request->input('disorder_id'));
            });
        }

        if ($request->filled('specialty_id')) {
            $query->whereHas('specialties', function ($q) use ($request) {
                $q->where('specialties.id', $request->input('specialty_id'));
            });
        }

        $prefectures = json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true);
        $disorders = Disorder::all();
        $specialties = Specialty::all();
        $hospitals = $query->paginate(20); // 20件
        // これでは、適切に検索できなかった、調べておく$hospitals = Hospital::with(['reviews', 'disorders', 'specialties'])

        
        // 表示するビューを指定
        return view('admin.index', compact('hospitals', 'prefectures', 'disorders', 'specialties'));
        }

        // 病院詳細
        public function show($id)
        {
            //　病院の詳細データを表示する　findOrFaill()の意味。id探すけど、見つからなかったら即エラーを投げて止める
            $hospital = Hospital::with(['disorders', 'specialties', 'reviews'])->findOrFail($id);

            //　診療曜日を表示する PREG_SPLIT_NO_EMPTYで空白は除外。$hospital->day_of_weekの中身（月火水等）を一文字ずつ分解して配列にする。曜日は月、火、水となって$openDaysに代入される
            $openDays = preg_split('//u', $hospital->day_of_week, -1, PREG_SPLIT_NO_EMPTY);
            $days = ['月', '火', '水', '木', '金', '土', '日'];

            $amSlots = [];
            $pmSlots = [];

            foreach ($days as $day) {
                // DBから取得した$openDaysが$daysの中に含まれるか確認。午前、午後が空でなければ、⭕️と表記。空なら'-'にする。
                $amSlots[] = (in_array($day, $openDays) && !empty($hospital->am_open)) ? '◯' : '−';
                $pmSlots[] = (in_array($day, $openDays) && !empty($hospital->pm_open)) ? '◯' : '−';
            }

            return view('admin.show', compact('hospital', 'amSlots', 'pmSlots'));
        }

        // 病院登録フォーム
        public function create() {
            // 都道府県、治療法、専門分野、特徴タグに表示するために、キーにjsonファイルを設定？配列形式で
            return view('admin.create', [
                'prefectures' => json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true),
                'treatments' => json_decode(file_get_contents(storage_path('app/json/treatments.json')), true),
                'specialties' => json_decode(file_get_contents(storage_path('app/json/specialties.json')), true),
                'disorders' => json_decode(file_get_contents(storage_path('app/json/disorders.json')), true),
                'features' => json_decode(file_get_contents(storage_path('app/json/features.json')), true),
            ]);
        }

        public function store(Request $request)
        {   
            //  バリデーションルール
            $validated = $request->validate([
                // 検索に必要なものだけ、空欄NGとする。{フィールド名}.{ルール名} => エラーメッセージ。
                'name' => 'required|string|max:255', // 検索・表示に絶対いる
                'prefecture' => 'required|string',   // 検索に絶対いる
                'disorders' => 'required|string',    // 検索に必要
                'specialties' => 'required|string',  // 必要

                // エラーが出るので必須に
                'address' => 'required|string|max:100',

                'type' => 'nullable|string|in:hospital,clinic',
                'homepage_url' => 'nullable|url',
                'map_url' => 'nullable|url',
                'station' => 'nullable|string',
                'day_of_week' => ['nullable', 'regex:/^[月火水木金土日祝祭]+$/u'],// 診療曜日は「月火水木金」のような形式でないと入力されないよう制限。アウトプット時に文字列をカンマで区切って配列にするため
                'am_open' => ['nullable', 'regex:/^\d{2}:\d{2}〜\d{2}:\d{2}$/'],//  例: 08:30〜12:00
                'pm_open' => ['nullable', 'regex:/^\d{2}:\d{2}〜\d{2}:\d{2}$/'],//中間テーブルに保存する際は、IDも必要だから配列nする？
                'treatment' => 'nullable|string',            
                'feature' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                

            ], [ // エラーメッセージを作成
                'name.required' => '病院名は必須項目です。',
                'address.required' => '所在地は必須項目です',
                'prefecture.required' => '都道府県は必須項目です。',
                'disorders.required' => '対象疾患は必須項目です。',
                'specialties.required' => '専門外来は必須項目です。',
                'day_of_week.regex' => '診療曜日は「月火水木金」のように入力してください（カンマや「曜」は不要です）',
                'am_open.regex' => '診療時間（午前）は「8:30〜12:00」のように入力してください',
                'pm_open.regex' => '診療時間（午後）は「13:00〜17:00」のように入力してください',
                'address.max' => '所在地は100文字以内で入力してください。',
                'homepage_url.url' => 'HPのURLの形式が正しくありません（例：https://example.com）',
                'map_url.url' => '地図URLの形式が正しくありません（例：https://maps.google.com/〜）',
                'phone.max' => '電話番号は20文字以内で入力してください。',
            ]);

            //  病院情報を保存
            $hospital = Hospital::create($validated);

            // 中間テーブル処理（Disorder/SpecialtyをIDに変換)ビュー側でカンマ区切りの単なる文字列で送られくるため、コントローラ側で配列に変換し直す。whereInは配列出ないと機能しない。
            $disorderNames = explode(',', $request->input('disorders'));
            $specialtyNames = explode(',', $request->input('specialties'));

            $disorderIds = \App\Models\Disorder::whereIn('name', $disorderNames)->pluck('id')->toArray();
            $specialtyIds = \App\Models\Specialty::whereIn('name', $specialtyNames)->pluck('id')->toArray();
            // 中間テーブルにも登録しないと検索することができない。
            $hospital->disorders()->sync($disorderIds);
            $hospital->specialties()->sync($specialtyIds);

            return redirect()->route('admin.hospitals.create')->with('success', '病院情報を登録しました');
            
            /*
            // 診療曜日は「月火水木金」のような形式でないと入力されないよう制限。アウトプット時に文字列をカンマで区切って配列にするため
            $request->validate([
                'day_of_week' => [
                    'required',
                    'regex:/^[月火水木金土日]+$/u',
                ],
            ], [
                'day_of_week.regex' => '診療曜日は「月火水木金」のように入力してください（カンマや「曜」は不要です）',
            ]);
            

            //　一旦、入力された情報は全て保存する、バリデーションは後で行う
            $hospital = Hospital::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'type' => $request->input('type'), 
                'homepage_url' => $request->input('homepage_url'), 
                'map_url' => $request->input('map_url'),
                'prefecture' => $request->input('prefecture'), 
                'station' => $request->input('station'), 
                'day_of_week' => $request->input('day_of_week'),
                'am_open' => $request->input('am_open'), 
                'pm_open' => $request->input('pm_open'), 
                'treatment' => $request->input('treatment'), 
                'feature' => $request->input('feature'),
                'phone' => $request->input('phone'),
            ]);

            return redirect()->route('admin.hospitals.index')->with('success','病院情報を登録しました');
            */
            
        }


        // 病院削除処理
        public function destroy($id)
        {
            $hospital = Hospital::findOrFail($id);
            $hospital->delete();

            return redirect()->route('admin.hospitals.index')->with('success', '病院を削除しました');
        }

        // 病院編集フォーム
        public function edit($id) {
            $hospital = Hospital::findOrFail($id); // ← これがないと$hospitalが定義されない
            
            // 都道府県、治療法、専門分野、特徴タグに表示するために、キーにjsonファイルを設定？配列形式で
            return view('admin.edit', [
                'hospital' => $hospital, 
                'prefectures' => json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true),
                'treatments' => json_decode(file_get_contents(storage_path('app/json/treatments.json')), true),
                'specialties' => json_decode(file_get_contents(storage_path('app/json/specialties.json')), true),
                'disorders' => json_decode(file_get_contents(storage_path('app/json/disorders.json')), true),
                'features' => json_decode(file_get_contents(storage_path('app/json/features.json')), true),
            ]);
        }

        //　更新機能。storeから移植
        public function update(Request $request, $id) {
            $hospital = Hospital::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'prefecture' => 'required|string',
                'disorders' => 'required|string',
                'specialties' => 'required|string',
                'address' => 'required|string|max:100',

                'type' => 'nullable|string|in:hospital,clinic',
                'homepage_url' => 'nullable|url',
                'map_url' => 'nullable|url',
                'station' => 'nullable|string|max:255',
                'day_of_week' => ['nullable', 'regex:/^[月火水木金土日祝祭]+$/u'],
                'am_open' => ['nullable', 'regex:/^\d{2}:\d{2}〜\d{2}:\d{2}$/'],
                'pm_open' => ['nullable', 'regex:/^\d{2}:\d{2}〜\d{2}:\d{2}$/'],
                'treatment' => 'nullable|string|max:255',
                'feature' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
            ], [ // エラーメッセージを作成
                'name.required' => '病院名は必須項目です。',
                'address.required' => '所在地は必須項目です',
                'prefecture.required' => '都道府県は必須項目です。',
                'disorders.required' => '対象疾患は必須項目です。',
                'specialties.required' => '専門外来は必須項目です。',
                'day_of_week.regex' => '診療曜日は「月火水木金」のように入力してください（カンマや「曜」は不要です）',
                'am_open.regex' => '診療時間（午前）は「8:30〜12:00」のように入力してください',
                'pm_open.regex' => '診療時間（午後）は「13:00〜17:00」のように入力してください',
                'address.max' => '所在地は100文字以内で入力してください。',
                'homepage_url.url' => 'HPのURLの形式が正しくありません（例：https://example.com）',
                'map_url.url' => '地図URLの形式が正しくありません（例：https://maps.google.com/〜）',
                'phone.max' => '電話番号は20文字以内で入力してください。',
            ]);

            // 本体の更新   
            $hospital->update($validated);

            // 中間テーブルの更新
            $disorderNames = explode(',', $request->input('disorders'));
            $specialtyNames = explode(',', $request->input('specialties'));

            $disorderIds = \App\Models\Disorder::whereIn('name', $disorderNames)->pluck('id')->toArray();
            $specialtyIds = \App\Models\Specialty::whereIn('name', $specialtyNames)->pluck('id')->toArray();

            $hospital->disorders()->sync($disorderIds);
            $hospital->specialties()->sync($specialtyIds);

            return redirect()->route('admin.hospitals.edit', $hospital->id)->with('success', '更新が完了しました');
        }
}