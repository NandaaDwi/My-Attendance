<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentClass;
use App\Models\Major;
use Illuminate\Http\Request;

class StudentClassController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $majors = Major::orderBy('name')->get();

        $classes = StudentClass::with('major')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.student_class.index', compact('classes', 'majors', 'search'));
    }

    public function show(StudentClass $studentClass)
    {
        $students = $studentClass->students()->with('user')->paginate(10);
        return view('admin.student_class.show', compact('studentClass', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'required|exists:majors,id',
        ]);

        StudentClass::create($validated);

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, StudentClass $studentClass)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'major_id' => 'required|exists:majors,id',
        ]);

        $studentClass->update($validated);

        return back()->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(StudentClass $studentClass)
    {
        $studentClass->delete();

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}