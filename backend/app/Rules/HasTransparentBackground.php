<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasTransparentBackground implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) || ! is_object($value) || ! method_exists($value, 'getRealPath')) {
            return;
        }

        $path = $value->getRealPath();
        $size = is_string($path) ? @getimagesize($path) : false;

        if (
            $size === false ||
            $size[0] < 800 ||
            $size[1] < 800 ||
            $size[0] > 4096 ||
            $size[1] > 4096
        ) {
            $fail('Logo harus berupa PNG atau WebP valid berukuran 800 x 800 sampai 4096 x 4096 piksel.');

            return;
        }

        $contents = @file_get_contents($path);
        $image = is_string($contents) ? @imagecreatefromstring($contents) : false;

        if ($image === false) {
            $fail('Logo tidak dapat dibaca. Gunakan file PNG atau WebP yang valid.');

            return;
        }

        $stepX = max(1, (int) floor(imagesx($image) / 128));
        $stepY = max(1, (int) floor(imagesy($image) / 128));
        $hasTransparency = false;

        for ($y = 0; $y < imagesy($image) && ! $hasTransparency; $y += $stepY) {
            for ($x = 0; $x < imagesx($image); $x += $stepX) {
                $color = imagecolorat($image, $x, $y);
                $alpha = imageistruecolor($image)
                    ? ($color & 0x7F000000) >> 24
                    : imagecolorsforindex($image, $color)['alpha'];

                if ($alpha > 0) {
                    $hasTransparency = true;
                    break;
                }
            }
        }

        imagedestroy($image);

        if (! $hasTransparency) {
            $fail('Logo harus memiliki latar transparan. Hapus latar lalu simpan sebagai PNG atau WebP.');
        }
    }
}
