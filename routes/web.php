<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHospitalController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\Auth\AdminLoginController;

/*ウェルカム画面のへルートのため、コメントアウトコメントアウト
Route::get('/', function () {
    return view('welcome');
});
*/

// ユーザー用の病院一覧ページ表示
Route::get('/', [HospitalController::class, 'index'])->name('hospital.index');

Route::get('/result',[HospitalController::class,'resultView'])->name('hospital.result');

// ユーザー用の病院詳細ページ表示
Route::get('/hospitals/{id}', [HospitalController::class, 'show'])->name('hospital.show');

// 管理者ログインへのページ
Route::get('/admin', [AdminLoginController::class, 'login'])->name('admin.login.form');
// 管理者ログイン情報を受け取る
Route::post('/admin/login', [AdminLoginController::class,'loginPost'])->name('admin.login.post');

// 管理者ログアウト
Route::post('/admin/logout',[AdminLoginController::class, 'logout'])->name('admin.logout');

// 管理者画面のページを表示
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
     // 一覧ページ（GET）
     Route::get('/hospitals', [AdminHospitalController::class, 'index'])->name('admin.hospitals.index');

     // 作成ページ（GET）← 固定URLを優先的に上に！
    Route::get('/hospitals/create', [AdminHospitalController::class, 'create'])->name('admin.hospitals.create');

    // 保存機能
    Route::post('/hospitals', [AdminHospitalController::class, 'store'])->name('admin.hospitals.store');

    // 編集ページ（GET）
    Route::get('/hospitals/{id}/edit', [AdminHospitalController::class, 'edit'])->name('admin.hospitals.edit');

    //　更新機能
    Route::put('/hospitals/{id}', [AdminHospitalController::class, 'update'])->name('admin.hospitals.update');

    // 詳細ページ（GET）← 変数部分（{id}）は最後に！
    Route::get('/hospitals/{id}', [AdminHospitalController::class, 'show'])->name('admin.hospitals.show');

    // 削除処理（DELETE）
    Route::delete('/hospitals/{id}', [AdminHospitalController::class, 'destroy'])->name('admin.hospitals.destroy');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
