<?php

// app/Http/Controllers/parent/DashboardParentController.php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\User;
use App\Models\ParentDetail;
use App\Models\StudentDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardParentController extends Controller
{
    public function __construct()
    {
        // Terapkan middleware 'parent_student' ke semua metode di controller ini
        $this->middleware('parent_student');
    }

    public function index()
    {
        $user = Auth::user();
        // Menggunakan relasi 'parentDetails' (plural) karena User HAS MANY ParentDetail
        // Eager load studentDetail dan user dari studentDetail untuk efisiensi
        $parentDetails = $user->parentDetails()->with('studentDetail.user')->get();

        // Mengumpulkan semua objek StudentDetail dari setiap ParentDetail
        $children = collect();
        foreach ($parentDetails as $parentDetailEntry) {
            if ($parentDetailEntry->studentDetail) {
                $children->push($parentDetailEntry->studentDetail);
            }
        }

        // Jika tidak ada anak yang ditemukan untuk orang tua ini
        if ($children->isEmpty()) {
            return view('parent.dashboard', [
                'parentName' => $user->name,
                'childrenData' => [], // Kirim array kosong jika tidak ada anak
                'activeAcademicYear' => null, // Atau berikan nilai default lain jika tidak ada tahun ajaran
            ]);
        }

        // Dapatkan tahun ajaran aktif
        $activeAcademicYear = AcademicYear::where('active', true)->first();

        if (!$activeAcademicYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif yang ditemukan. Harap hubungi administrator.');
        }

        // Siapkan data untuk setiap anak untuk dashboard
        $childrenData = [];
        foreach ($children as $child) {
            $rawAttendances = Attendance::where('student_id', $child->id)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->get();

            $summary = [
                'Present' => $rawAttendances->where('status', 'Present')->count(),
                'Sick' => $rawAttendances->where('status', 'Sick')->count(),
                'Excused' => $rawAttendances->where('status', 'Excused')->count(),
                'Absent' => $rawAttendances->where('status', 'Absent')->count(),
                'Total' => $rawAttendances->count(),
            ];

            // Siapkan data untuk grafik
            // Pastikan format tanggal konsisten (YYYY-MM-DD) untuk JavaScript
            $attendanceDataForGraph = $rawAttendances->map(function ($attendance) {
                return [
                    'date' => Carbon::parse($attendance->date)->format('Y-m-d'), // Changed to YYYY-MM-DD
                    'status' => $attendance->status,
                ];
            })->toArray();

            $childrenData[] = [
                'id' => $child->id,
                'name' => $child->user->name, // Mengambil nama dari relasi user di StudentDetail
                'nisn' => $child->nisn,
                'summary' => $summary,
                'attendanceDataForGraph' => $attendanceDataForGraph, // This now contains ALL attendance data for the active year
            ];
        }

        return view('parent.dashboard', [
            'parentName' => $user->name,
            'childrenData' => $childrenData,
            'activeAcademicYear' => $activeAcademicYear,
        ]);
    }

    /**
     * Mengambil data absensi detail untuk seorang anak dengan pagination dan filter via AJAX.
     */
    public function getDetailedAttendances(Request $request)
    {
        $user = Auth::user();
        $childId = $request->input('child_id');

        // Verifikasi apakah child_id yang diminta benar-benar terkait dengan orang tua yang sedang login.
        // Cek apakah ada record di parent_details yang link ke user ini DAN ke student_id ini.
        $isChildOfParent = $user->parentDetails()->where('student_id', $childId)->exists();

        if (!$isChildOfParent) {
            return response()->json(['message' => 'Anak tidak ditemukan atau bukan anak Anda.'], 403);
        }

        $activeAcademicYear = AcademicYear::where('active', true)->first();

        if (!$activeAcademicYear) {
            return response()->json(['message' => 'Tahun ajaran aktif tidak ditemukan.'], 404);
        }

        $query = Attendance::where('student_id', $childId) // Gunakan langsung $childId yang sudah diverifikasi
            ->where('academic_year_id', $activeAcademicYear->id)
            ->with('absenceReason')
            ->orderBy('date', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('search_date')) {
            // Ensure the input date is parsed correctly
            try {
                $searchDate = Carbon::createFromFormat('Y-m-d', $request->input('search_date'))->format('Y-m-d');
                $query->whereDate('date', $searchDate);
            } catch (\Exception $e) {
                // Log the error or return a bad request response if date format is invalid
                return response()->json(['message' => 'Format tanggal tidak valid.'], 400);
            }
        }

        // Filter berdasarkan status
        if ($request->filled('status_filter') && $request->input('status_filter') !== '') {
            $query->where('status', $request->input('status_filter'));
        }

        $detailedAttendances = $query->paginate(7); // 7 item per halaman

        $transformedAttendances = $detailedAttendances->through(function ($attendance) {
            return [
                'id' => $attendance->id,
                'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                'formatted_date' => Carbon::parse($attendance->date)->locale('id')->isoFormat('D MMMM Y'),
                'status' => $attendance->status,
                'absence_reason' => $attendance->absenceReason ? $attendance->absenceReason->name : '-',
                'note' => $attendance->note ?? '-',
            ];
        });

        return response()->json($transformedAttendances);
    }
}