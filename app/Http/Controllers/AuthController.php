<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Services\CaptchaService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    protected $captcha;

    public function __construct(CaptchaService $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * The login method does NOT use captcha validation.
     * reCAPTCHA is only used on the Register page.
     */
    private function getLoginRoleInfo(?string $role): ?array
    {
        $roleLabels = [
            'admin' => ['icon' => '👨‍💼', 'title' => 'Login Admin', 'desc' => 'Masuk sebagai Administrator'],
            'staff' => ['icon' => '👷', 'title' => 'Login Staff', 'desc' => 'Masuk sebagai Staff'],
            'manager' => ['icon' => '👔', 'title' => 'Login Manager', 'desc' => 'Masuk sebagai Manager'],
        ];

        return $role ? ($roleLabels[$role] ?? null) : null;
    }

    // Tampilkan halaman Login (dengan role berdasarkan URL)
    // Google reCAPTCHA TIDAK digunakan di halaman Login
    public function showLogin(Request $request)
    {
        $routeName = $request->route()->getName();

        $roleMap = [
            'admin.login' => 'admin',
            'staff.login' => 'staff',
            'manager.login' => 'manager',
        ];

        $role = $roleMap[$routeName] ?? null;
        $roleInfo = $this->getLoginRoleInfo($role);

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
        $siteKey = $this->captcha->getSiteKey();

        return view('auth.register', compact('role', 'roleInfo', 'siteKey'));
    }

    // Proses Register menggunakan RegisterRequest + Google reCAPTCHA
    public function register(RegisterRequest $request)
    {
        // 1. Validasi Google reCAPTCHA server-side
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $verified = $this->captcha->verify($recaptchaResponse);

        if (!$verified) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ])->withInput();
        }

        // 2. Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // 3. Fire Registered Event - otomatis mengirim Email Verification via Notification
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email verifikasi: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            // Jangan crash aplikasi, user tetap terdaftar
        }

        // 4. Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi sebelum login.');
    }

    // Proses Login - TANPA Google reCAPTCHA
    public function login(Request $request)
    {
        // 1. Validasi input (hanya email & password, tanpa captcha)
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan email
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
        // 3. Cek password dulu sebelum attempt untuk pesan error yang lebih baik
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 4. Cek apakah email sudah diverifikasi
        if (!$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email Anda terlebih dahulu.',
            ])->onlyInput('email');
        }

        // 5. Cek password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 6. Proses login
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
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
