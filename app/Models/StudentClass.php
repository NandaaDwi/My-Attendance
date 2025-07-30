<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    protected $table = 'classes';

     protected $fillable = ['name', 'major_id'];

    //  public $timestamps = false;


     public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function students()
    {
        return $this->hasMany(StudentDetail::class, 'class_id');
    }

    
}
