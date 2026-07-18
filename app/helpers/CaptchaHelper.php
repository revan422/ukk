<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class CaptchaHelper
{
    /**
     * Generate captcha code dan simpan di session
     */
    public static function generate()
    {
        $code = strtoupper(Str::random(5));
        session(['captcha_code' => $code]);
        return $code;
    }

    /**
     * Validasi captcha (case-insensitive)
     */
    public static function validate($input)
    {
        $stored = session('captcha_code', '');
        return strtolower(trim($input)) === strtolower($stored);
    }

    /**
     * Generate captcha image (base64)
     */
    public static function image()
    {
        $code = self::generate();

        // Create image
        $width = 150;
        $height = 50;
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $noiseColor = imagecolorallocate($image, 200, 200, 200);

        // Fill background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Add noise
        for ($i = 0; $i < 50; $i++) {
            imagesetpixel($image, rand(0, $width), rand(0, $height), $noiseColor);
        }

        // Add text
        $fontSize = 5;
        $x = 10;
        $y = 15;

        for ($i = 0; $i < strlen($code); $i++) {
            imagestring($image, $fontSize, $x + ($i * 25), $y, $code[$i], $textColor);
        }

        // Output as base64
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($imageData);
    }
}
