<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCmsImage implements ValidationRule
{
    public function __construct(
        private readonly int $minWidth,
        private readonly int $minHeight,
        private readonly ?float $aspectRatio = null,
        private readonly string $aspectRatioLabel = '',
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_string($value) || ! is_object($value) || ! method_exists($value, 'getRealPath')) {
            return;
        }

        $path = $value->getRealPath();
        $size = is_string($path) ? @getimagesize($path) : false;

        if ($size === false || ! in_array($size['mime'] ?? null, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            $fail('Gambar harus berupa JPG, PNG, atau WebP yang valid.');

            return;
        }

        [$width, $height] = $size;

        if ($width < $this->minWidth || $height < $this->minHeight) {
            $fail("Resolusi gambar minimal {$this->minWidth} x {$this->minHeight} piksel.");

            return;
        }

        if ($this->aspectRatio === null) {
            return;
        }

        $actualRatio = $width / max($height, 1);
        if (abs($actualRatio - $this->aspectRatio) > 0.025) {
            $fail("Rasio gambar harus {$this->aspectRatioLabel}. Gunakan fitur potong gambar sebelum menyimpan.");
        }
    }
}
