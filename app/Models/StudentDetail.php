<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;

class StudentDetail extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentClass()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function studentDetail()
    {
        return $this->hasOne(StudentDetail::class, 'user_id'); //
    }

     public function parentDetails() // Gunakan nama plural karena 'hasMany'
    {
        return $this->hasMany(ParentDetail::class, 'student_id');
    }

    protected $fillable = [
        'user_id',
        'nis',
        'nisn',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'phone',
        'class_id',
        'status',
        'photo',
    ];
}
