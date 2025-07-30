<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentDetail;
use App\Models\EmployeeDetail;
use App\Models\ParentDetail;
use App\Models\Major;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $roleFilter = $request->role;

        // Fetch all users based on search and role filters, without pagination
        $users = User::with(['studentDetail.class', 'employeeDetail.class', 'parentDetail.student.user'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->when($roleFilter, function ($query) use ($roleFilter) {
                $query->where('role', $roleFilter);
            })
            ->orderBy('created_at', 'desc')
            ->get() // Use get() instead of paginate()
            ->map(function ($user) { // Use map to transform the collection
                $userData = $user->toArray();

                if ($user->studentDetail) {
                    $userData = array_merge($userData, [
                        'nis' => $user->studentDetail->nis,
                        'nisn' => $user->studentDetail->nisn,
                        'gender' => $user->studentDetail->gender,
                        'place_of_birth' => $user->studentDetail->place_of_birth,
                        'date_of_birth' => $user->studentDetail->date_of_birth,
                        'religion' => $user->studentDetail->religion,
                        'address' => $user->studentDetail->address,
                        'phone' => $user->studentDetail->phone,
                        'photo' => $user->studentDetail->photo,
                        'class_name' => optional($user->studentDetail->class)->name,
                        'status' => $user->studentDetail->status,
                    ]);
                } elseif ($user->employeeDetail) {
                    $userData = array_merge($userData, [
                        'nip' => $user->employeeDetail->nip,
                        'gender' => $user->employeeDetail->gender,
                        'place_of_birth' => $user->employeeDetail->place_of_birth,
                        'date_of_birth' => $user->employeeDetail->date_of_birth,
                        'religion' => $user->employeeDetail->religion,
                        'address' => $user->employeeDetail->address,
                        'phone' => $user->employeeDetail->phone,
                        'photo' => $user->employeeDetail->photo,
                        'class_name' => optional($user->employeeDetail->class)->name,
                    ]);
                } elseif ($user->parentDetail) {
                    $userData = array_merge($userData, [
                        'full_name' => $user->parentDetail->full_name,
                        'occupation' => $user->parentDetail->occupation,
                        'relationship' => $user->parentDetail->relationship,
                        'parent_email' => $user->parentDetail->email,
                        'phone' => $user->parentDetail->phone,
                        'address' => $user->parentDetail->address,
                        'student_name' => optional(optional($user->parentDetail->student)->user)->name,
                    ]);
                }

                return $userData;
            });

        // Pass the entire collection to the view
        return view('admin.users.index', compact('users', 'search', 'roleFilter'));
    }


    public function create()
    {
        $majors = Major::all();
        $classes = StudentClass::all();
        return view('admin.users.create', compact('majors', 'classes'));
    }

    public function store(Request $request)
    {
        // General validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,staff,homeroom_teacher,student,parent_student',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        switch ($request->role) {
            case 'student':
                $request->validate([
                    'nis' => 'required|string|unique:student_details,nis',
                    'nisn' => 'required|string|unique:student_details,nisn',
                    'gender' => 'required|in:M,F',
                    'class_id' => 'required|exists:classes,id',
                    'status' => 'required|in:active,inactive',
                    'place_of_birth' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'religion' => 'nullable|string',
                    'address' => 'nullable|string',
                    'phone' => 'nullable|string',
                ]);

                StudentDetail::create([
                    'user_id' => $user->id,
                    'nis' => $request->nis,
                    'nisn' => $request->nisn,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->place_of_birth,
                    'date_of_birth' => $request->date_of_birth,
                    'religion' => $request->religion,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'class_id' => $request->class_id,
                    'status' => $request->status,
                    'photo' => $photoPath,
                ]);
                break;

            case 'staff':
            case 'homeroom_teacher':
                $request->validate([
                    'nip' => 'required|string|unique:employee_details,nip',
                    'gender' => 'required|in:M,F',
                    'place_of_birth' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'religion' => 'nullable|string',
                    'address' => 'nullable|string',
                    'phone' => 'nullable|string',
                    'class_id' => $request->role == 'homeroom_teacher' ? 'nullable|exists:classes,id' : 'nullable',
                ]);

                EmployeeDetail::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->place_of_birth,
                    'date_of_birth' => $request->date_of_birth,
                    'religion' => $request->religion,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'class_id' => $request->role == 'homeroom_teacher' ? $request->class_id : null,
                    'photo' => $photoPath,
                ]);
                break;

            case 'admin':
                // Admin doesn't need additional details, just basic user info
                break;

            case 'parent_student':
                $request->validate([
                    'full_name' => 'required|string',
                    'occupation' => 'required|string',
                    'relationship' => 'required|string',
                    'student_id' => 'required|exists:student_details,id',
                    'phone' => 'required|string|unique:parent_details,phone',
                    'parent_email' => 'nullable|email|unique:parent_details,email',
                    'address' => 'nullable|string',
                ]);

                ParentDetail::create([
                    'user_id' => $user->id,
                    'full_name' => $request->full_name,
                    'occupation' => $request->occupation,
                    'relationship' => $request->relationship,
                    'email' => $request->parent_email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'student_id' => $request->student_id,
                ]);
                break;
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load(['studentDetail.class', 'employeeDetail.class', 'parentDetail.student.user']);

        $userData = $user->toArray();

        if ($user->studentDetail) {
            $userData = array_merge($userData, [
                'nis' => $user->studentDetail->nis,
                'nisn' => $user->studentDetail->nisn,
                'gender' => $user->studentDetail->gender,
                'place_of_birth' => $user->studentDetail->place_of_birth,
                'date_of_birth' => $user->studentDetail->date_of_birth,
                'religion' => $user->studentDetail->religion,
                'address' => $user->studentDetail->address,
                'phone' => $user->studentDetail->phone,
                'photo' => $user->studentDetail->photo,
                'class_id' => $user->studentDetail->class_id,
                'class_name' => optional($user->studentDetail->class)->name,
                'status' => $user->studentDetail->status,
            ]);
        } elseif ($user->employeeDetail) {
            $userData = array_merge($userData, [
                'nip' => $user->employeeDetail->nip,
                'gender' => $user->employeeDetail->gender,
                'place_of_birth' => $user->employeeDetail->place_of_birth,
                'date_of_birth' => $user->employeeDetail->date_of_birth,
                'religion' => $user->employeeDetail->religion,
                'address' => $user->employeeDetail->address,
                'phone' => $user->employeeDetail->phone,
                'photo' => $user->employeeDetail->photo,
                'class_id' => $user->employeeDetail->class_id,
                'class_name' => optional($user->employeeDetail->class)->name,
            ]);
        } elseif ($user->parentDetail) {
            $userData = array_merge($userData, [
                'full_name' => $user->parentDetail->full_name,
                'occupation' => $user->parentDetail->occupation,
                'relationship' => $user->parentDetail->relationship,
                'parent_email' => $user->parentDetail->email,
                'phone' => $user->parentDetail->phone,
                'address' => $user->parentDetail->address,
                'student_id' => $user->parentDetail->student_id,
                'student_name' => optional(optional($user->parentDetail->student)->user)->name,
            ]);
        }

        return response()->json($userData);
    }

    public function edit(User $user)
    {
        $majors = Major::all();
        $classes = StudentClass::all();
        $user->load(['studentDetail.class', 'employeeDetail.class', 'parentDetail.student.user']);

        return view('admin.users.edit', compact('user', 'majors', 'classes'));
    }

    public function update(Request $request, User $user)
    {
        // General validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $photoPath = null;
        // Handle new photo and delete old photo if exists
        if ($request->hasFile('photo')) {
            // Delete old photo from storage
            if ($user->employeeDetail && $user->employeeDetail->photo) {
                Storage::disk('public')->delete($user->employeeDetail->photo);
            }
            if ($user->studentDetail && $user->studentDetail->photo) {
                Storage::disk('public')->delete($user->studentDetail->photo);
            }

            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update details based on role
        switch ($user->role) {
            case 'student':
                $request->validate([
                    'nis' => ['required', 'string', Rule::unique('student_details', 'nis')->ignore(optional($user->studentDetail)->id)],
                    'nisn' => ['required', 'string', Rule::unique('student_details', 'nisn')->ignore(optional($user->studentDetail)->id)],
                    'gender' => 'required|in:M,F',
                    'class_id' => 'required|exists:classes,id',
                    'status' => 'required|in:active,inactive',
                    'place_of_birth' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'religion' => 'nullable|string',
                    'address' => 'nullable|string',
                    'phone' => 'nullable|string',
                ]);

                $studentData = [
                    'nis' => $request->nis,
                    'nisn' => $request->nisn,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->place_of_birth,
                    'date_of_birth' => $request->date_of_birth,
                    'religion' => $request->religion,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'class_id' => $request->class_id,
                    'status' => $request->status,
                ];

                if ($photoPath) {
                    $studentData['photo'] = $photoPath;
                }

                $user->studentDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $studentData
                );
                break;

            case 'staff':
            case 'homeroom_teacher':
                $request->validate([
                    'nip' => ['required', 'string', Rule::unique('employee_details', 'nip')->ignore(optional($user->employeeDetail)->id)],
                    'gender' => 'required|in:M,F',
                    'place_of_birth' => 'nullable|string',
                    'date_of_birth' => 'nullable|date',
                    'religion' => 'nullable|string',
                    'address' => 'nullable|string',
                    'phone' => 'nullable|string',
                    'class_id' => $user->role == 'homeroom_teacher' ? 'nullable|exists:classes,id' : 'nullable',
                ]);

                $employeeDetailData = [
                    'nip' => $request->nip,
                    'gender' => $request->gender,
                    'place_of_birth' => $request->place_of_birth,
                    'date_of_birth' => $request->date_of_birth,
                    'religion' => $request->religion,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'class_id' => $user->role == 'homeroom_teacher' ? $request->class_id : null,
                ];

                if ($photoPath) {
                    $employeeDetailData['photo'] = $photoPath;
                }

                $user->employeeDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $employeeDetailData
                );
                break;

            case 'parent_student':
                $request->validate([
                    'full_name' => 'required|string',
                    'occupation' => 'required|string',
                    'relationship' => 'required|string',
                    'student_id' => 'required|exists:student_details,id',
                    'phone' => ['required', 'string', Rule::unique('parent_details', 'phone')->ignore(optional($user->parentDetail)->id)],
                    'parent_email' => ['nullable', 'email', Rule::unique('parent_details', 'email')->ignore(optional($user->parentDetail)->id)],
                    'address' => 'nullable|string',
                ]);

                $parentData = [
                    'full_name' => $request->full_name,
                    'occupation' => $request->occupation,
                    'relationship' => $request->relationship,
                    'email' => $request->parent_email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'student_id' => $request->student_id,
                ];

                $user->parentDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $parentData
                );
                break;
        }

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->employeeDetail && $user->employeeDetail->photo) {
            Storage::disk('public')->delete($user->employeeDetail->photo);
        }
        if ($user->studentDetail && $user->studentDetail->photo) {
            Storage::disk('public')->delete($user->studentDetail->photo);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }
}
