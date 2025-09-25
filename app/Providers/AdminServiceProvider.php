<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
      // // 관리자 페이지 대상
      //   Gate::define('access-admin-page', function ($user) {
      //       return in_array($user->role, ['admin', 'manager']);
      //   });
      //   // 관리자만
      //   Gate::define('is-admin', function ($user) {
      //       return $user->role === 'admin';
      //   });
    }
}
