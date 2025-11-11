<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
      // auth check
      if(auth()->check()) {
        return redirect()->intended(route('index'));
      }

      // Check 'get' or 'post' HTTP METHOD
      if($request->isMethod('post')) {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        if(Auth::attempt($credentials, $remember)) {
          $request->session()->regenerate();
          return redirect()->intended(route('index'));
        }
        return back()->withErrors([
          'email' => '메일 또는 패스워드가 틀렸습니다.',
        ])->withInput($request->only('email'));
      }

      return view('main.login');
    }
    public function logout(Request $request)
    {
      // 로그인 정보 삭제
      Auth::guard('web')->logout();
      // 세션 파기
      $request->session()->invalidate();
      // 토큰 갱신
      $request->session()->regenerateToken();
      
      return redirect()->route('index');
    }
    public function register(Request $request)
    {
      // 이미 로그인된 사용자는 메인으로 리다이렉트 (중복 가입 방지)
      if (auth()->check()) {
        return redirect()->intended(route('index'));
      }

      // GET 요청: 회원가입 폼 렌더링
      if ($request->isMethod('get')) {
        return view('main.register');
      }

      // POST 요청: 회원가입 처리
      // 1) 입력값 검증 (현업 기본 규칙: 필수/형식/중복 확인)
      $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        // 비밀번호 확인 필드가 없다면 confirmed 규칙은 제외
        'password' => ['required', 'string', 'min:8'],
      ]);

      try {
        // 2) 트랜잭션으로 User + Student 동시 생성 (무결성 보장)
        $user = DB::transaction(function () use ($validated) {
          $user = new User();
          $user->name = $validated['name'];
          $user->email = $validated['email'];
          $user->password = Hash::make($validated['password']);
          $user->save();

          // Student 행 생성: user_id 유니크 제약
          \App\Models\Student::create(['user_id' => $user->id]);

          return $user;
        });

        // 3) 가입 후 자동 로그인 (일반 UX)
        Auth::login($user);

        // 4) 세션 재발급 (세션 고정화 방지)
        $request->session()->regenerate();

        // 5) 메인 페이지로 이동
        return redirect()->intended(route('index'));
      } catch (\Throwable $e) {
        // 에러 핸들링: 로깅 후 사용자 친화적 메시지 반환
        report($e);
        return back()->withErrors([
          'register' => '일시적인 오류로 회원가입에 실패했습니다. 잠시 후 다시 시도해주세요.',
        ])->withInput($request->only('name', 'email'));
      }
    }
}