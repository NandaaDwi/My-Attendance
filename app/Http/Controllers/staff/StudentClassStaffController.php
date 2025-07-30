<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentClass;
use App\Models\Major;

class StudentClassStaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $majors = Major::orderBy('name')->get();

        $query = StudentClass::with('major')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name');

        $classes = $query->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'classes' => $classes->items(),
                'pagination' => [
                    'current_page' => $classes->currentPage(),
                    'last_page' => $classes->lastPage(),
                    'per_page' => $classes->perPage(),
                    'total' => $classes->total(),
                ]
            ]);
        }

        return view('staff.student_class.index', compact('classes', 'majors', 'search'));
    }
}
