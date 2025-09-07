<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\AuthService; // 서비스 클래스 import

class AuthController extends Controller
{
    protected $authService;

    // 생성자 주입
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // 서비스의 메서드를 호출하여 비즈니스 로직 실행
        if ($this->authService->processLogin($credentials)) {
            // 인증 성공 시 리디렉션
            return redirect()->intended('admin/dashboard');
        }

        // 인증 실패 시 에러 메시지 반환
        return back()->withErrors([
            'email' => '메일 또는 패스워드가 틀렸습니다.',
        ]);
    }
    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }
}