<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show Profile Settings Page
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update Profile Data (Personal Info)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'passport_number' => 'nullable|string|max:50',
        ]);

        $fillableFields = ['name', 'email', 'date_of_birth', 'gender', 'passport_number'];
        $dataToUpdate = [];

        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                $dataToUpdate[$field] = ($value === '' && in_array($field, ['date_of_birth', 'gender', 'passport_number'])) ? null : $value;
            }
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
        }

        return back()->with('success', 'Data pribadi berhasil diperbarui!');
    }

    /**
     * Update Account Security (Password)
     */
    public function updateSecurity(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
