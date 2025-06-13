<?php

namespace App\Http\Controllers\Auth;

use App\Models\Hospital;
use App\Models\Disorder;
use App\Models\Specialty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    //　管理者ログイン
    public function login() {
        return view('admin.login');
    }
 
    // ログイン情報を処理
    public function loginPost(Request $request) {

        $credentials = $request->only('email','password');

        if (Auth::attempt($credentials,$request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/hospitals');
        }

        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません',
        ])->onlyInput('email');

    }

    // ログアウト
    public function logout(Request $request) {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');

    }  
}