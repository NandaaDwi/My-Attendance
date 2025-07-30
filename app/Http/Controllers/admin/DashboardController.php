<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentDetail;
use App\Models\StudentClass;
use App\Models\Attendance;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $activeYear = AcademicYear::where('active', true)->first();

        // Total siswa aktif
        $jumlahSiswa = StudentDetail::where('status', 'active')->count();

        // Total kelas
        $jumlahKelas = StudentClass::count();

        // Absensi hari ini berdasarkan status
        $absensiHariIni = [];
        if ($activeYear) {
            $absensiHariIni = Attendance::where('date', $today->toDateString())
                ->where('academic_year_id', $activeYear->id)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        }

        $stats = (object) [
            'hadir' => $absensiHariIni['Present'] ?? 0,
            'sakit' => $absensiHariIni['Sick'] ?? 0,
            'izin'  => $absensiHariIni['Excused'] ?? 0,
            'alpha' => $absensiHariIni['Absent'] ?? 0,
        ];

        // Data untuk grafik hari ini (default)
        $chartData = $this->getChartData('daily', $activeYear);

        return view('admin.dashboard', compact(
            'jumlahSiswa',
            'jumlahKelas',
            'stats',
            'chartData'
        ));
    }

    public function getData(Request $request)
    {
        $filter = $request->input('filter', 'daily');
        $activeYear = AcademicYear::where('active', true)->first();
        
        $chartData = $this->getChartData($filter, $activeYear);
        
        return response()->json($chartData);
    }

    private function getChartData($filter, $activeYear)
    {
        $today = Carbon::today();
        $statsData = [
            'hadir' => [],
            'sakit' => [],
            'izin' => [],
            'alpha' => [],
        ];
        $labels = [];
        $chartType = 'line';

        if (!$activeYear) {
            return compact('statsData', 'labels', 'chartType');
        }

        switch ($filter) {
            case 'daily':
                // Untuk daily, gunakan doughnut chart
                $counts = Attendance::where('date', $today->toDateString())
                    ->where('academic_year_id', $activeYear->id)
                    ->selectRaw('status, COUNT(*) as total')
                    ->groupBy('status')
                    ->pluck('total', 'status')
                    ->toArray();

                $labels = ['Hadir', 'Sakit', 'Izin', 'Alpha'];
                $statsData = [
                    $counts['Present'] ?? 0,
                    $counts['Sick'] ?? 0,
                    $counts['Excused'] ?? 0,
                    $counts['Absent'] ?? 0
                ];
                $chartType = 'doughnut';
                break;

            case 'weekly':
                $start = $today->copy()->subDays(6);
                $period = CarbonPeriod::create($start, $today);
                
                foreach ($period as $date) {
                    $labels[] = $date->format('D, d M');

                    $counts = Attendance::where('date', $date->toDateString())
                        ->where('academic_year_id', $activeYear->id)
                        ->selectRaw('status, COUNT(*) as total')
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

                    $statsData['hadir'][] = $counts['Present'] ?? 0;
                    $statsData['sakit'][] = $counts['Sick'] ?? 0;
                    $statsData['izin'][] = $counts['Excused'] ?? 0;
                    $statsData['alpha'][] = $counts['Absent'] ?? 0;
                }
                $chartType = 'bar';
                break;

            case 'monthly':
                $start = $today->copy()->startOfMonth();
                $end = $today->copy()->endOfMonth();
                $period = CarbonPeriod::create($start, $end);
                
                foreach ($period as $date) {
                    $labels[] = $date->format('d');

                    $counts = Attendance::where('date', $date->toDateString())
                        ->where('academic_year_id', $activeYear->id)
                        ->selectRaw('status, COUNT(*) as total')
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

                    $statsData['hadir'][] = $counts['Present'] ?? 0;
                    $statsData['sakit'][] = $counts['Sick'] ?? 0;
                    $statsData['izin'][] = $counts['Excused'] ?? 0;
                    $statsData['alpha'][] = $counts['Absent'] ?? 0;
                }
                $chartType = 'line';
                break;

            case 'yearly':
                $year = $today->year;
                for ($m = 1; $m <= 12; $m++) {
                    $labels[] = Carbon::createFromDate($year, $m, 1)->format('M Y');

                    $counts = Attendance::whereYear('date', $year)
                        ->whereMonth('date', $m)
                        ->where('academic_year_id', $activeYear->id)
                        ->selectRaw('status, COUNT(*) as total')
                        ->groupBy('status')
                        ->pluck('total', 'status')
                        ->toArray();

                    $statsData['hadir'][] = $counts['Present'] ?? 0;
                    $statsData['sakit'][] = $counts['Sick'] ?? 0;
                    $statsData['izin'][] = $counts['Excused'] ?? 0;
                    $statsData['alpha'][] = $counts['Absent'] ?? 0;
                }
                $chartType = 'line';
                break;
        }

        return compact('statsData', 'labels', 'chartType');
    }
}
