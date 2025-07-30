<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major;
use App\Models\StudentClass;
use App\Models\StudentDetail;
use App\Models\Attendance;
use App\Models\AcademicYear;
use App\Models\AttendanceHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceRecapStaffController extends Controller
{
    private $statusMap = [
        'Present' => 'Hadir',
        'Excused' => 'Izin',
        'Sick' => 'Sakit',
        'Absent' => 'Alpa',
    ];

    public function index(Request $request)
    {
        $search = $request->get('search');
        $view = $request->get('view', 'majors');
        $majorId = $request->get('major_id');
        $classId = $request->get('class_id');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        $data = [];
        $breadcrumb = [];
        $noRecapMessage = null;
        $classDisplayName = null;

        Log::info("AttendanceRecapController@index called. View: {$view}, Major ID: {$majorId}, Class ID: {$classId}, Date: {$date}, Search: {$search}");

        if ($view === 'majors') {
            $majors = Major::withCount(['studentClasses as student_classes_count'])
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->get();

            $data = $majors;
            $breadcrumb = ['Pilih Jurusan'];
        } elseif ($view === 'classes' && $majorId) {
            $major = Major::findOrFail($majorId);
            $classes = StudentClass::where('major_id', $majorId)
                ->withCount(['students as active_students_count' => function ($query) {
                    $query->where('status', 'active');
                }])
                ->when($search, function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->get();

            $data = $classes;
            $breadcrumb = ['Rekap Absensi', $major->name];
        } elseif ($view === 'students' && $classId) {
            $class = StudentClass::with('major')->findOrFail($classId);
            $classDisplayName = $class->name;

            // Check if any attendance has been recorded for this class on this date
            $hasAttendanceForDate = Attendance::where('date', $date)
                ->whereHas('student', function ($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->exists();

            if (!$hasAttendanceForDate && Carbon::parse($date)->isFuture()) {
                // If it's a future date and no attendance is recorded
                $data = []; // Ensure data is empty to trigger the "no data" message
                $noRecapMessage = "Belum ada absensi dilakukan untuk kelas ini pada tanggal " . Carbon::parse($date)->format('d M Y') . ".";
            } else {
                // Proceed with fetching students and existing attendances
                $students = StudentDetail::with('user', 'studentClass')
                    ->where('class_id', $classId)
                    ->where('status', 'active')
                    ->when($search, function ($query) use ($search) {
                        $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    })
                    ->orderBy('id', 'asc')
                    ->get();

                $existingAttendances = Attendance::where('date', $date)
                    ->whereHas('student', function ($query) use ($classId) {
                        $query->where('class_id', $classId);
                    })
                    ->get()
                    ->keyBy('student_id');

                $data = $students->map(function ($student) use ($existingAttendances, $date) {
                    $attendance = $existingAttendances->get($student->id);
                    return [
                        'student_id' => $student->id,
                        'nis' => $student->nis,
                        'name' => $student->user->name ?? '-',
                        'class_name' => $student->studentClass->name ?? '-',
                        'status' => $attendance ? $attendance->status : 'Absent', // Default to Absent if no record
                        'note' => $attendance ? $attendance->note : null,
                        'attendance_id' => $attendance ? $attendance->id : null,
                    ];
                });

                if ($data->isEmpty()) {
                    if ($search) {
                        $noRecapMessage = "Tidak ada siswa ditemukan untuk pencarian '{$search}' pada kelas ini.";
                    } else {
                        $noRecapMessage = "Tidak ada siswa aktif di kelas ini pada tanggal " . Carbon::parse($date)->format('d M Y') . ".";
                    }
                } else {
                    $noRecapMessage = null; // Reset message if data is found
                }
            }
            $breadcrumb = ['Rekap Absensi', $class->major->name, $class->name];
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'view' => $view,
                'noRecapMessage' => $noRecapMessage,
                'date' => $date,
                'majorId' => $majorId,
                'classId' => $classId,
                'classDisplayName' => $classDisplayName,
            ]);
        }

        return view('staff.attendance-recap.index', compact(
            'data',
            'view',
            'majorId',
            'classId',
            'search',
            'breadcrumb',
            'date',
            'noRecapMessage'
        ));
    }

    public function update(Request $request)
    {
        Log::info('AttendanceRecapController@update called.');
        Log::info('Request data: ' . json_encode($request->all()));

        $request->validate([
            'class_id' => 'required|exists:student_classes,id',
            'date' => 'required|date_format:Y-m-d',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|exists:student_details,id',
            'attendances.*.status' => 'required|in:Present,Excused,Sick,Absent',
            'attendances.*.note' => 'nullable|string|max:255',
        ]);

        $classId = $request->class_id;
        $date = $request->date;
        $submittedAttendances = $request->attendances;
        $officerId = Auth::id();

        if (!$officerId) {
            Log::error('Officer ID not found. User not authenticated.');
            return response()->json(['success' => false, 'message' => 'User not authenticated.'], 401);
        }

        DB::beginTransaction();
        try {
            foreach ($submittedAttendances as $submittedAttendance) {
                $studentId = $submittedAttendance['student_id'];
                $newStatus = $submittedAttendance['status'];
                $newNote = $submittedAttendance['note'] ?? null;

                Log::info("Processing attendance for student_id: {$studentId}, date: {$date}, newStatus: {$newStatus}, newNote: {$newNote}");

                $attendance = Attendance::where('student_id', $studentId)
                    ->where('date', $date)
                    ->first();

                $changeDescription = '';

                if ($attendance) {
                    Log::info("Existing attendance found for student_id: {$studentId}. Current status: {$attendance->status}, note: {$attendance->note}");
                    if ($attendance->status !== $newStatus || ($attendance->note ?? '') !== ($newNote ?? '')) {
                        $oldStatus = $attendance->status;
                        $oldNote = $attendance->note;

                        $studentName = $attendance->student->user->name ?? 'N/A';
                        $changeDescription .= "Mengubah absensi siswa {$studentName} pada tanggal " . Carbon::parse($date)->format('d M Y') . ": ";
                        if ($oldStatus !== $newStatus) {
                            $changeDescription .= "Status dari '" . $this->statusMap[$oldStatus] . "' menjadi '" . $this->statusMap[$newStatus] . "'. ";
                        }
                        if (($oldNote ?? '') !== ($newNote ?? '')) {
                            $changeDescription .= "Catatan dari '" . ($oldNote ?? 'kosong') . "' menjadi '" . ($newNote ?? 'kosong') . "'. ";
                        }

                        $attendance->status = $newStatus;
                        $attendance->note = $newNote;
                        $attendance->officer_id = $officerId;
                        $attendance->save();
                        Log::info("Attendance updated for student_id: {$studentId}. Change: {$changeDescription}");

                        AttendanceHistory::create([
                            'attendance_id' => $attendance->id,
                            'user_id' => $officerId,
                            'change' => trim($changeDescription),
                        ]);
                        Log::info("Attendance history logged for attendance_id: {$attendance->id}");
                    } else {
                        Log::info("No changes detected for student_id: {$studentId}. Skipping update.");
                    }
                } else {
                    Log::info("No existing attendance found for student_id: {$studentId}. Creating new record.");
                    $academicYear = AcademicYear::where('active', true)->first();
                    if (!$academicYear) {
                        Log::error('No active academic year found for new attendance creation.');
                        throw new \Exception('Tidak ada tahun ajaran aktif.');
                    }
                    $newlyCreatedAttendance = Attendance::create([
                        'student_id' => $studentId,
                        'date' => $date,
                        'status' => $newStatus,
                        'reason_id' => null,
                        'note' => $newNote,
                        'officer_id' => $officerId,
                        'academic_year_id' => $academicYear->id,
                    ]);

                    $studentName = StudentDetail::find($studentId)->user->name ?? 'N/A';
                    $changeDescription = "Menambahkan absensi baru untuk siswa {$studentName} pada tanggal " . Carbon::parse($date)->format('d M Y') . " dengan status '" . $this->statusMap[$newStatus] . "'";
                    if ($newNote) {
                        $changeDescription .= " dan catatan: '" . $newNote . "'";
                    }
                    Log::info("New attendance created for student_id: {$studentId}. Change: {$changeDescription}");

                    AttendanceHistory::create([
                        'attendance_id' => $newlyCreatedAttendance->id,
                        'user_id' => $officerId,
                        'change' => trim($changeDescription),
                    ]);
                    Log::info("Attendance history logged for new attendance_id: {$newlyCreatedAttendance->id}");
                }
            }

            DB::commit();
            Log::info('Attendance update transaction committed successfully.');
            return response()->json(['success' => true, 'message' => 'Absensi berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attendance update failed: ' . $e->getMessage() . ' Stack trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan perubahan: ' . $e->getMessage()], 500);
        }
    }
}