<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = AcademicYear::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $academicYears = $query->latest()->get();

        return view('admin.academic_year.index', compact('academicYears', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:academic_years,name',
            'active' => ['required', 'in:0,1'],
        ]);

        AcademicYear::create([
            'name' => $request->name,
            'active' => $request->active,
        ]);

        return back()->with('success', 'Tahun ajaran berhasil dibuat');
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'name' => 'required|unique:academic_years,name,' . $academicYear->id,
            'active' => ['required', 'in:0,1'],
        ]);

        $academicYear->update([
            'name' => $request->name,
            'active' => $request->active,
        ]);

        return back()->with('success', 'Tahun ajaran berhasil diupdate');
    }

    public function destroy(AcademicYear $academicYear)
    {
        $academicYear->delete();
        return back()->with('success', 'Tahun ajaran berhasil dihapus');
    }
}
