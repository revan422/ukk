<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CaptchaService
{
    /**
     * Verify Google reCAPTCHA v2 response token.
     *
     * @param string $response The reCAPTCHA response token from the frontend
     * @param string|null $remoteIp Optional user IP address
     * @return bool True if verification succeeds
     */
    public function verify(string $response, ?string $remoteIp = null): bool
    {
        if (empty($response)) {
            return false;
        }

        $secret = config('captcha.nocaptcha_secret', env('NOCAPTCHA_SECRET', ''));

        if (empty($secret)) {
            Log::error('CaptchaService: NOCAPTCHA_SECRET is not configured');
            return false;
        }

        try {
            $googleResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $response,
                'remoteip' => $remoteIp ?? request()->ip(),
            ]);

            if ($googleResponse->successful()) {
                $body = $googleResponse->json();

                if (isset($body['success']) && $body['success'] === true) {
                    return true;
                }

                // Log the error codes for debugging
                if (isset($body['error-codes'])) {
                    Log::warning('CaptchaService: reCAPTCHA verification failed', [
                        'error-codes' => $body['error-codes'],
                    ]);
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('CaptchaService: reCAPTCHA verification exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the reCAPTCHA site key for frontend rendering.
     *
     * @return string
     */
    public function getSiteKey(): string
    {
        return config('captcha.nocaptcha_sitekey', env('NOCAPTCHA_SITEKEY', ''));
    }
}
