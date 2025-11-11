<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AuthService; // 서비스 클래스 import
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    protected $authService;

    // 생성자 주입
    public function __construct(AuthService $authService)
    {
      // 서비스에 위임하지않음
      // $this->authService = $authService;
    }

    public function login(Request $request)
    {
        // 이미 로그인된 경우 대시보드로 이동
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        if ($request->isMethod('post')) {
          $credentials = $request->only('email', 'password');
          // $remember = $request->boolean('remember');
          // 사용자 인증 로직을 서비스에서 처리
          if (Auth::attempt($credentials)) {
              // 인증 성공
              request()->session()->regenerate();
              return redirect()->intended('admin/dashboard');
          }
          // 인증 실패 시 에러 메시지와 입력값 유지
          return back()->withErrors([
              'email' => '메일 또는 패스워드가 틀렸습니다.',
          ])->withInput($request->only('email'));
        }

        // GET 요청이면 로그인 폼 반환
        return view('admin.login');
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}