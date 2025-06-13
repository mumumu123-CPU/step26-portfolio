<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\Disorder;
use App\Models\Specialty;

class HospitalController extends Controller
{   // 検索フォームで大阪と入力→GET方式でLaravelにデータが飛ぶ→それを$requestが引き受ける→大元のRequestは母体→母体に引き受けさせたら、毎回母体を作り直さないといけない→だから、子であるインスタンスに引き受けさせる
    public function index(Request $request)
    {   // withの引数はモデル内の関数名
        $query = Hospital::with(['reviews', 'disorders', 'specialties']);

        // データ取得。->paginate(20); はget()の進化系
        $hospitals = $query->paginate(21); 

        // 診療時間がAM、PMでない場合の処理
        foreach ($hospitals as $hospital) {
            // $hospitalオブジェクトに->am_displayを追加
            $hospital->am_display = $hospital->am_open
            ? 'AM：' . $hospital->am_open
            : '午前診療なし';
            $hospital->pm_display = $hospital->pm_open
            ? 'PM：' . $hospital->pm_open
            : '午後診療なし';
        }

        // ドロップダウン用データ
        $prefectures = json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true);
        $disorders = Disorder::all();
        $specialties = Specialty::all();
        // ビューで使用するためにcompact()で加工？
        return view('hospitals.index', compact('hospitals', 'prefectures', 'disorders', 'specialties'));
    }

    public function resultView(Request $request) //　触らない
    {   // withの引数はモデル内の関数名
        $query = Hospital::with(['reviews', 'disorders', 'specialties']);

        // 検索条件がある場合は絞り込み
        if ($request->filled('prefecture')) {
            // whereでprefectureというカラムを見てと指定。$request->input('prefecture')フォームでinputされたprefectureという値を受け取る。大阪なら、ここに大阪が入る。繋げるとフォームで入力されたprefectureとう値を見て、それに合うものを病院情報の都道府県というカラムの中から取得してきてという意味。
            // $query->where('カラム名', '値');
            $query->where('prefecture', $request->input('prefecture'));
        }
        // よくわからない。　whereHasが不明。
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

        // データ取得。->paginate(20); にはget()の進化系
        $hospitals = $query->paginate(21); 

             // 診療時間がAM、PMでない場合の処理
        foreach ($hospitals as $hospital) {
            // $hospitalオブジェクトに->am_displayを追加
            $hospital->am_display = $hospital->am_open
            ? 'AM：' . $hospital->am_open
            : '午前診療なし';
            $hospital->pm_display = $hospital->pm_open
            ? 'PM：' . $hospital->pm_open
            : '午後診療なし';
        }

        // ドロップダウン用データ
        $prefectures = json_decode(file_get_contents(storage_path('app/json/prefectures.json')), true);
        $disorders = Disorder::all();
        $specialties = Specialty::all();
        // ビューで使用するためにcompact()で加工？
        return view('hospitals.result', compact('hospitals', 'prefectures', 'disorders', 'specialties'));
    }

    public function show($id) {
        $hospital = Hospital::with(['reviews','disorders','specialties'])->findOrFail($id);
        return view('hospitals.show',compact('hospital'));
    }
    
    
}
