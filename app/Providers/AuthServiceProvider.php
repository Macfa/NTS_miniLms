<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Program;
use App\Models\Chapter;
use App\Policies\ProgramPolicy;
use App\Policies\ChapterPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 버전 12 에서는 정책 연결 자동적 처리
        // Program::clads => ProgramPolicy::class,
        // Chapter::class => ChapterPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
      // 관리자 전권(모든 Policy 메소드 패스)
      Gate::before(function ($user) {
          return $user->role === 'admin' ? true : null;
      });

      // 기존 Blade / route 에서 사용하는 Gate 이름 유지
      Gate::define('is-admin', fn($user) => $user->role === 'admin');

      // 관리자 + 강사 공용 admin 섹션 접근 허용
      Gate::define('access-admin-page', fn($user) => in_array($user->role, ['admin','manager']));
    }
}
