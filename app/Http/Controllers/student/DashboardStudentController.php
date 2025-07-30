<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear; // Import model AcademicYear
use App\Models\Attendance; // Import model Attendance
use App\Models\StudentDetail; // Import model StudentDetail
use Carbon\Carbon; // Digunakan untuk manipulasi tanggal
use Illuminate\Support\Facades\Auth;

class DashboardStudentController extends Controller
{
     public function index()
    {
        $user = Auth::user();
        $studentDetail = $user->studentDetail;

        if (!$studentDetail) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki detail siswa yang terkait.');
        }

        $activeAcademicYear = AcademicYear::where('active', true)->first();

        if (!$activeAcademicYear) {
            return redirect()->back()->with('error', 'Tidak ada tahun ajaran aktif yang ditemukan. Harap hubungi administrator.');
        }

        // Ambil semua data absensi mentah untuk ringkasan dan grafik
        $rawAttendances = Attendance::where('student_id', $studentDetail->id)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->with('absenceReason')
            ->orderBy('date', 'desc')
            ->get(); // Get all for summary and initial graph data

        // Hitung ringkasan jumlah absensi berdasarkan status
        $summary = [
            'Present' => $rawAttendances->where('status', 'Present')->count(),
            'Sick' => $rawAttendances->where('status', 'Sick')->count(),
            'Excused' => $rawAttendances->where('status', 'Excused')->count(),
            'Absent' => $rawAttendances->where('status', 'Absent')->count(),
            'Total' => $rawAttendances->count(),
        ];

        // Mempersiapkan data absensi untuk grafik (hanya date dan status)
        $attendanceDataForGraph = $rawAttendances->map(function ($attendance) {
            return [
                'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                'status' => $attendance->status,
            ];
        })->toArray();

        // Data awal untuk detail absensi (akan dimuat via AJAX dengan pagination)
        // Kita hanya perlu URL endpoint di sini, tidak perlu mengambil data paginated awal di index()
        // karena akan diambil oleh Alpine.js via AJAX.

        return view('student.dashboard', [
            'studentDetail' => $studentDetail,
            'summary' => $summary,
            'attendanceDataForGraph' => $attendanceDataForGraph,
            'studentName' => $user->name,
        ]);
    }

    /**
     * Mengambil data absensi detail dengan pagination dan filter via AJAX.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetailedAttendances(Request $request)
    {
        $user = Auth::user();
        $studentDetail = $user->studentDetail;

        if (!$studentDetail) {
            return response()->json(['message' => 'Detail siswa tidak ditemukan.'], 404);
        }

        $activeAcademicYear = AcademicYear::where('active', true)->first();

        if (!$activeAcademicYear) {
            return response()->json(['message' => 'Tahun ajaran aktif tidak ditemukan.'], 404);
        }

        $query = Attendance::where('student_id', $studentDetail->id)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->with('absenceReason')
            ->orderBy('date', 'desc');

        // Filter berdasarkan tanggal
        if ($request->filled('search_date')) {
            $searchDate = Carbon::parse($request->input('search_date'))->format('Y-m-d');
            $query->whereDate('date', $searchDate);
        }

        // Filter berdasarkan status
        if ($request->filled('status_filter') && $request->input('status_filter') !== '') {
            $query->where('status', $request->input('status_filter'));
        }

        $detailedAttendances = $query->paginate(7); // Misalnya 7 item per halaman

        // Transformasi data untuk frontend (sesuaikan jika perlu lebih banyak/sedikit data)
        $transformedAttendances = $detailedAttendances->through(function ($attendance) {
            return [
                'id' => $attendance->id,
                'date' => Carbon::parse($attendance->date)->format('Y-m-d'),
                'formatted_date' => Carbon::parse($attendance->date)->locale('id')->isoFormat('D MMMM Y'), // For display
                'status' => $attendance->status,
                'absence_reason' => $attendance->absenceReason ? $attendance->absenceReason->name : '-',
                'note' => $attendance->note ?? '-',
            ];
        });

        // Kembalikan data dalam format JSON, termasuk link pagination
        return response()->json($transformedAttendances);
    }
}
