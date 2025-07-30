<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class);
    }

    // Relasi untuk employee detail (jika role = staff, admin, wali kelas)
    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    // Relasi untuk parent detail (jika role = parent)
    public function parentDetail()
    {
        return $this->hasOne(ParentDetail::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'officer_id');
    }

    public function attendanceHistory()
    {
        return $this->hasMany(AttendanceHistory::class, 'user_id');
    }

    public function parentDetails() // Gunakan nama plural karena 'hasMany'
    {
        return $this->hasMany(ParentDetail::class, 'user_id');
    }
    
    protected $fillable = [
        'name',
        'email',
        'password',
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
}
