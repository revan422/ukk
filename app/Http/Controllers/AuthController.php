<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\CaptchaHelper;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan halaman Login (dengan role berdasarkan URL)
    public function showLogin(Request $request)
    {
        $routeName = $request->route()->getName();

        $roleMap = [
            'admin.login' => 'admin',
            'staff.login' => 'staff',
            'manager.login' => 'manager',
        ];

        $role = $roleMap[$routeName] ?? null;

        $roleLabels = [
            'admin' => ['icon' => '👨‍💼', 'title' => 'Login Admin', 'desc' => 'Masuk sebagai Administrator'],
            'staff' => ['icon' => '👷', 'title' => 'Login Staff', 'desc' => 'Masuk sebagai Staff'],
            'manager' => ['icon' => '👔', 'title' => 'Login Manager', 'desc' => 'Masuk sebagai Manager'],
        ];

        $roleInfo = $role ? $roleLabels[$role] : null;

        return view('auth.login', compact('role', 'roleInfo'));
    }

    // Tampilkan halaman Register (dengan role berdasarkan URL)
    public function showRegister(Request $request)
    {
        $routeName = $request->route()->getName();

        $roleMap = [
            'admin.register' => 'admin',
            'customer.register' => 'customer',
            'staff.register' => 'staff',
            'manager.register' => 'manager',
        ];

        $role = $roleMap[$routeName] ?? 'customer';

        $roleLabels = [
            'admin' => ['icon' => '👨‍💼', 'title' => 'Registrasi Admin', 'desc' => 'Daftar sebagai Administrator'],
            'customer' => ['icon' => '👤', 'title' => 'Registrasi Customer', 'desc' => 'Daftar sebagai Penumpang'],
            'staff' => ['icon' => '👷', 'title' => 'Registrasi Staff', 'desc' => 'Daftar sebagai Staff'],
            'manager' => ['icon' => '👔', 'title' => 'Registrasi Manager', 'desc' => 'Daftar sebagai Manager'],
        ];

        $roleInfo = $roleLabels[$role];

        return view('auth.register', compact('role', 'roleInfo'));
    }

    // Proses Register
    public function register(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,manager,staff,customer',
        ]);

        // 2. Validasi Custom Checkbox Captcha
        if (!session('captcha_verified')) {
            return back()->withErrors([
                'captcha' => 'Silakan konfirmasi bahwa Anda bukan robot.',
            ])->withInput();
        }
        session()->forget('captcha_verified');

        // 3. Buat user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // 4. Fire Registered Event - ini akan otomatis mengirim Email Verification via Notification
        event(new Registered($user));

        // 5. JANGAN auto-login! Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi sebelum login.');
    }

    // Proses Login
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Validasi Custom Checkbox Captcha
        if (!session('captcha_verified')) {
            return back()->withErrors([
                'captcha' => 'Silakan konfirmasi bahwa Anda bukan robot.',
            ])->withInput($request->only('email'));
        }
        session()->forget('captcha_verified');

        // 3. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 4. Cek apakah user ada
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 5. Cek apakah email sudah diverifikasi
        if (!$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email Anda terlebih dahulu.',
            ])->onlyInput('email');
        }

        // 6. Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 7. Proses login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Validasi role jika ada expected_role
            if ($request->has('expected_role')) {
                $expectedRole = $request->input('expected_role');

                if ($user->role !== $expectedRole) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return back()->withErrors([
                        'email' => "Akun ini bukan untuk {$expectedRole}. Silakan login dengan akun yang sesuai.",
                    ])->onlyInput('email');
                }
            }

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif ($user->role === 'staff') {
                return redirect()->route('staff.dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Verifikasi Checkbox Captcha (AJAX)
    public function verifyCheckboxCaptcha(Request $request)
    {
        session(['captcha_verified' => true]);
        return response()->json(['success' => true]);
    }

    // Tampilkan Form Lupa Password
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Kirim Link Reset Password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // Tampilkan Form Reset Password
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Proses Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password Anda telah berhasil direset! Silakan login.')
            : back()->withErrors(['email' => __($status)]);
    }
}
