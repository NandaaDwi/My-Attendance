<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentClass;

class EmployeeDetail extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    protected $fillable = [
        'user_id',
        'nip',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'phone',
        'class_id',   // nullable, untuk wali kelas
        'photo',
    ];
}
