<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Admin;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id', 'id');
    }
    public function student()
    {
        return $this->hasOne(Student::class, 'user_id', 'id');
    }
    public function manager()
    {
        return $this->hasOne(Manager::class, 'user_id', 'id');
    }
    public function getStatusAttribute(): string
    {
        return $this->attributes['status'] ? '활성화' : '비활성화';
    }
    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            // 관리자/학생 레코드 소프트 삭제
            if ($user->admin) {
                $user->admin->delete();
            }
            if ($user->student) {
                $user->student->delete();
            }
            if ($user->manager) {
                $user->manager->delete();
            }
        });
    }
}