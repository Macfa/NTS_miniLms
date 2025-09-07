<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * 사용자를 인증하고 로그인 처리를 수행합니다.
     *
     * @param array $credentials
     * @return bool
     */
    public function processLogin(array $credentials): bool
    {
        // 사용자 인증 로직을 서비스에서 처리
        if (Auth::attempt($credentials, true)) {
            // 인증 성공
            return true;
        }

        // 인증 실패
        return false;
    }
}