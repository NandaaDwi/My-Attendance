<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'status',
        'reason_id',
        'note',
        'officer_id',
        'academic_year_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Status constants
    const STATUS_PRESENT = 'Present';
    const STATUS_EXCUSED = 'Excused';
    const STATUS_SICK    = 'Sick';
    const STATUS_ABSENT  = 'Absent';

    // Status labels for display
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PRESENT => 'Hadir',
            self::STATUS_EXCUSED => 'Izin',
            self::STATUS_SICK    => 'Sakit',
            self::STATUS_ABSENT  => 'Alpa',
        ];
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        return self::getStatusLabels()[$this->status] ?? $this->status;
    }

    // Relationships

    /**
     * Relasi ke detail siswa (StudentDetail)
     */
    public function studentDetail()
    {
        return $this->belongsTo(StudentDetail::class, 'student_id');
    }

    /**
     * Relasi ke user petugas pencatat absensi
     */
    public function officer()
    {
        return $this->belongsTo(User::class, 'officer_id');
    }

    /**
     * Relasi ke tahun ajaran
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * Relasi ke alasan ketidakhadiran (opsional)
     */

    public function absenceReason()
    {
        return $this->belongsTo(AbsenceReason::class, 'reason_id');
    }
    
    public function reasonDetail()
    {
        return $this->belongsTo(AbsenceReason::class, 'reason_id');
    }

    public function student()
    {
        return $this->belongsTo(\App\Models\StudentDetail::class, 'student_id');
    }

    public function history()
    {
        return $this->hasMany(AttendanceHistory::class);
    }
}
