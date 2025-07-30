<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major;
use App\Models\StudentClass;
use App\Models\StudentDetail;
use App\Models\Attendance;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceStaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $view = $request->get('view', 'majors');
        $majorId = $request->get('major_id');
        $classId = $request->get('class_id');
        
        $data = [];
        $breadcrumb = [];
        $existingAttendance = collect(); // Initialize as empty collection
        $attendanceForCurrentClassExists = false; // Flag to check if attendance for the current class exists

        if ($view === 'majors') {
            // Display all majors
            $majors = Major::with('studentClasses')
                ->when($search, function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->get();
            
            $data = $majors;
            $breadcrumb = ['Pilih Jurusan'];
            
        } elseif ($view === 'classes' && $majorId) {
            // Display classes based on major
            $major = Major::findOrFail($majorId);
            $classes = StudentClass::with(['students' => function($query) {
                    $query->where('status', 'active');
                }])
                ->where('major_id', $majorId)
                ->when($search, function($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->get();
            
            $data = $classes;
            $breadcrumb = ['Pilih Jurusan', $major->name];
            
        } elseif ($view === 'students' && $classId) {
            // Display students for attendance
            $class = StudentClass::with('major')->findOrFail($classId);
            $students = StudentDetail::with(['user', 'class.major'])
                ->where('class_id', $classId)
                ->where('status', 'active')
                ->when($search, function($query) use ($search) {
                    $q->whereHas('user', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                })
                ->orderBy('user_id')
                ->get();
            
            // Check if attendance already exists for today for this specific class
            $today = Carbon::today();
            $existingAttendanceRecords = Attendance::where('date', $today)
                ->whereHas('studentDetail', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->get();

            $existingAttendance = $existingAttendanceRecords->keyBy('student_id');

            // Determine if attendance for this class is already submitted for today
            if ($existingAttendanceRecords->count() > 0) {
                $attendanceForCurrentClassExists = true;
            }
            
            // Attach existing attendance status and note to each student object
            $data = $students->map(function($student) use ($existingAttendance) {
                $attendanceRecord = $existingAttendance->get($student->id);
                $student->existing_attendance_status = $attendanceRecord ? $attendanceRecord->status : null;
                $student->existing_attendance_note = $attendanceRecord ? $attendanceRecord->note : null;
                return $student;
            });

            $breadcrumb = ['Pilih Jurusan', $class->major->name, $class->name];
        }
        
        // Handle AJAX requests for real-time search
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'view' => $view,
                'majorId' => $majorId, // Pass back majorId for consistency
                'classId' => $classId, // Pass back classId for consistency
                'existingAttendance' => $existingAttendance->toArray(), // Pass existing attendance for initial state
                'attendanceForCurrentClassExists' => $attendanceForCurrentClassExists // Pass the new flag
            ]);
        }
        
        return view('staff.attendance.index', compact(
            'data', 'view', 'majorId', 'classId', 'search', 'breadcrumb', 'existingAttendance', 'attendanceForCurrentClassExists'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:Present,Excused,Sick,Absent',
            'attendance.*.reason' => 'nullable|string|max:255',
        ]);

        $academicYear = AcademicYear::where('active', true)->first();
        if (!$academicYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif.');
        }

        $today = Carbon::today();
        $class = StudentClass::with('major')->findOrFail($request->class_id);
        
        // Before deleting, check if attendance for this class already exists for today
        // This is a server-side double-check to prevent re-submission if the user bypasses JS disabling
        $existingAttendanceCount = Attendance::where('date', $today)
            ->whereHas('studentDetail', function($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->count();
            
        if ($existingAttendanceCount > 0) {
            return redirect()->back()->with('error', "Absensi untuk kelas {$class->name} ({$class->major->name}) sudah disimpan hari ini.");
        }

        // Delete existing attendance for today for the submitted students (should be none if the above check passes)
        Attendance::where('date', $today)
            ->whereIn('student_id', array_keys($request->attendance))
            ->delete();

        // Create new attendance records
        foreach ($request->attendance as $studentId => $data) {
            Attendance::create([
                'student_id' => $studentId,
                'date' => $today,
                'status' => $data['status'],
                'reason_id' => null, // Assuming reason_id is not used or handled elsewhere
                'note' => $data['reason'] ?? null,
                'officer_id' => Auth::id(),
                'academic_year_id' => $academicYear->id,
            ]);
        }

        return redirect()->back()->with('success', 
            "Absensi untuk kelas {$class->name} ({$class->major->name}) berhasil disimpan!"
        );
    }
}