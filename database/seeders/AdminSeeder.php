<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      DB::transaction(function() {
        $user = User::create([
            'name' => '관리자',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => Hash::make('admin'),
        ]);

        Admin::create([
            'user_id' => $user->id,
        ]);

        $user = User::create([
            'name' => '매니저',
            'email' => 'manager@manager.com',
            'role' => 'manager',
            'password' => Hash::make('manager'),
        ]);

        Manager::create([
            'user_id' => $user->id,
        ]);
      });
    }
}
