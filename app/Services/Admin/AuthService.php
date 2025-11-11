<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthService // 사용하지 않음 
{
    /**
     * 사용자를 인증하고 로그인 처리를 수행합니다.
     *
     * @param array $credentials
     * @return bool
     */
    public function processLogin(Request $request): bool
    {


        // 인증 실패
        return false;
    }
    /**
     * 현재 로그인 상태인지 확인
     */
    public function check(): bool
    {
        return Auth::check();
    }    
}