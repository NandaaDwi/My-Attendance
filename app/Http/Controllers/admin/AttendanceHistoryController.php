<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttendanceHistory;
use Carbon\Carbon;

class AttendanceHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $query = AttendanceHistory::with([
            'attendance.student.user', // Memuat relasi siswa dan pengguna yang terkait dengan absensi
            'attendance.officer',      // Memuat petugas yang mencatat absensi
            'user'                     // Memuat pengguna yang melakukan perubahan riwayat
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('change', 'like', "%{$search}%") // Mencari di kolom perubahan
                  ->orWhereHas('attendance.student.user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%"); // Mencari berdasarkan nama siswa
                  })
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%"); // Mencari berdasarkan nama pengguna yang mengubah
                  });
            });
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate); // Filter berdasarkan tanggal mulai perubahan
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate); // Filter berdasarkan tanggal akhir perubahan
        }

        $histories = $query->orderBy('created_at', 'desc')->paginate(15); // Mengurutkan berdasarkan waktu pembuatan terbaru

        return view('admin.history.index', compact('histories', 'search', 'startDate', 'endDate'));
    }
}

