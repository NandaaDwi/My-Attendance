<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Major;

class MajorStaffController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $query = Major::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $majors = $query->orderBy('name')->paginate(10)->withQueryString();

        if ($request->wantsJson()) {
            // Return JSON, include pagination meta for frontend (optional)
            return response()->json([
                'majors' => $majors->items(),
                'pagination' => [
                    'current_page' => $majors->currentPage(),
                    'last_page' => $majors->lastPage(),
                    'per_page' => $majors->perPage(),
                    'total' => $majors->total(),
                ]
            ]);
        }

        return view('staff.major.index', compact('majors', 'search'));
    }
}
