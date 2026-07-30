<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function getLoginRoleInfo(?string $role): ?array
    {
        $roleLabels = [
            'admin' => ['icon' => '👨‍💼', 'title' => 'Login Admin', 'desc' => 'Masuk sebagai Administrator'],
            'staff' => ['icon' => '👷', 'title' => 'Login Staff', 'desc' => 'Masuk sebagai Staff'],
            'manager' => ['icon' => '👔', 'title' => 'Login Manager', 'desc' => 'Masuk sebagai Manager'],
        ];

        return $role ? ($roleLabels[$role] ?? null) : null;
    }

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

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Untuk admin, staff, manager langsung verified dan redirect ke dashboard
        if (in_array($user->role, ['admin', 'staff', 'manager'])) {
            $user->markEmailAsVerified();
            Auth::login($user);

            $routes = [
                'admin' => 'admin.dashboard',
                'staff' => 'staff.dashboard',
                'manager' => 'manager.dashboard',
            ];

            return redirect()->route($routes[$user->role])
                ->with('success', 'Registrasi berhasil!');
        }

        // Untuk customer: kirim email verifikasi dan arahkan ke halaman verifikasi
        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email verifikasi saat register: ' . $e->getMessage());
        }

        // Generate URL verifikasi untuk ditampilkan langsung di halaman
        $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi.')
            ->with('verification_url', $verificationUrl);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Validasi reCAPTCHA - hanya jika ada input (tidak diwajibkan agar tidak menghalangi login)
        if ($request->has('g-recaptcha-response') && !empty($request->input('g-recaptcha-response'))) {
            $request->validate([
                'g-recaptcha-response' => 'captcha',
            ], [
                'g-recaptcha-response.captcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.',
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if (!$user->hasVerifiedEmail()) {
            return back()->withErrors([
                'email' => 'Silakan verifikasi email Anda terlebih dahulu.',
            ])->onlyInput('email');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            // Check if there's a redirect after login (for booking flow)
            $redirectAfterLogin = $request->session()->get('redirect_after_login');
            
            if ($redirectAfterLogin) {
                $request->session()->forget('redirect_after_login');
                return redirect($redirectAfterLogin);
            }

            // Redirect based on role
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

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

    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

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