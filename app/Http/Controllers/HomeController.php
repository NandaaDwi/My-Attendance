<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth'); // Ensure user is authenticated to access this controller
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Check user role and redirect to appropriate dashboard
        if ($user->role === 'student') { // Assuming 'role' column in 'users' table
            return redirect()->route('student.dashboard');
        } elseif ($user->role === 'parent_student') {
            return redirect()->route('parent.dashboard');
        }
        // You can add more roles here, e.g., 'admin', 'teacher'
        // elseif ($user->role === 'admin') {
        //     return redirect()->route('admin.dashboard');
        // }

        // Default fallback if role is not recognized or no specific dashboard exists
        return view('home');
    }
}