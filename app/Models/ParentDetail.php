<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentDetail extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    protected $fillable = [
        'user_id',
        'full_name',
        'occupation',
        'relationship',
        'email',
        'phone',
        'address',
        'student_id',
    ];

    public function children()
    {
        // Asumsi: tabel 'student_details' memiliki foreign key 'parent_id'
        // yang merujuk ke 'id' di tabel 'parent_details'
        return $this->hasMany(StudentDetail::class, 'parent_id');
    }

    public function studentDetail() // Gunakan nama singular karena 'belongsTo'
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }
}
