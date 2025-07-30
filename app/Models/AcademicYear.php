<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'name',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function attendances()
{
    return $this->hasMany(Attendance::class, 'academic_year_id'); //
}
}
