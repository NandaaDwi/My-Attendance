<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Show the settings modal content for Profile.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        return view('admin.settings.profile', compact('user'));
    }

    /**
     * Show the settings modal content for General settings (theme).
     *
     * @return \Illuminate\View\View
     */
    public function general()
    {
        return view('admin.settings.general');
    }

    /**
     * Show the settings modal content for Help.
     *
     * @return \Illuminate\View\View
     */
    public function help()
    {
        return view('admin.settings.help');
    }

    /**
     * Handle theme change.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,system',
        ]);

        // For simplicity, we'll store the theme preference in a session or a cookie.
        // In a real application, you might persist this in the user's database settings.
        session(['theme' => $request->theme]);

        return response()->json(['message' => 'Theme updated successfully.', 'theme' => $request->theme]);
    }
}