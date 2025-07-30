<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $majors = Major::when($search, function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->orderBy('name')->paginate(10)->withQueryString();

        return view('admin.major.index', compact('majors', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Major::create($request->only('name'));

        return redirect()->route('admin.major.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Major $major)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $major->update($request->only('name'));

        return redirect()->route('admin.major.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        $major->delete();

        return redirect()->route('admin.major.index')->with('success', 'Jurusan berhasil dihapus.');
    }
}
