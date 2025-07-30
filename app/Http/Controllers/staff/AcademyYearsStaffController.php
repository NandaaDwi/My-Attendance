<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademyYearsStaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = AcademicYear::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $academicYears = $query->latest()->get();

        // Jika request menerima JSON (fetch API) maka kembalikan JSON
        if ($request->wantsJson()) {
            return response()->json([
                'academicYears' => $academicYears,
            ]);
        }

        // Default return blade view
        return view('staff.academic_year.index', compact('academicYears', 'search'));
    }
}
