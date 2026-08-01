<?php

namespace Tests\Feature;

use App\Rules\HasTransparentBackground;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CmsLogoValidationTest extends TestCase
{
    public function test_transparent_png_logo_is_accepted(): void
    {
        $image = imagecreatetruecolor(800, 800);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        ob_start();
        imagepng($image);
        $transparentPng = ob_get_clean();
        imagedestroy($image);

        $file = UploadedFile::fake()->createWithContent('logo.png', $transparentPng);

        $validator = Validator::make(['logo' => $file], [
            'logo' => [new HasTransparentBackground],
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_opaque_logo_is_rejected(): void
    {
        $file = UploadedFile::fake()->image('logo.png', 800, 800);

        $validator = Validator::make(['logo' => $file], [
            'logo' => [new HasTransparentBackground],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertSame(
            'Logo harus memiliki latar transparan. Hapus latar lalu simpan sebagai PNG atau WebP.',
            $validator->errors()->first('logo'),
        );
    }
}
