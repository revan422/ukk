<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice page.
     */
    public function showNotice()
    {
        return view('auth.verify');
    }

    /**
     * Verify email using signed URL.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Cek apakah signature URL masih valid (belum expired)
        if (!$request->hasValidSignature()) {
            return view('auth.verify-expired', [
                'email' => $user->email,
            ]);
        }

        // Cek apakah hash cocok
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Tautan verifikasi tidak valid.');
        }

        // Cek apakah email sudah diverifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email sudah diverifikasi sebelumnya.');
        }

        // Tandai email sebagai terverifikasi
        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    /**
     * Send verification email to a specific email address (from expired page, no auth required).
     */
    public function sendToEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 'Email sudah diverifikasi sebelumnya.');
        }

        try {
            $user->sendEmailVerificationNotification();
            return back()->with('message', 'Email verifikasi berhasil dikirim ulang.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim ulang email verifikasi: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return back()->with('error', 'Gagal mengirim email verifikasi. Silakan coba lagi nanti.');
        }
    }

    /**
     * Resend email verification notification.
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah email sudah diverifikasi
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('info', 'Email Anda sudah diverifikasi.');
        }

        // Kirim ulang email verifikasi
        try {
            $user->sendEmailVerificationNotification();
            return back()->with('message', 'Email verifikasi berhasil dikirim ulang.');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim ulang email verifikasi: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
            return back()->with('error', 'Gagal mengirim email verifikasi. Silakan coba lagi nanti.');
        }
    }
}
