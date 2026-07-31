<?php

namespace Tests\Feature;

use App\Rules\ValidCmsImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class CmsImageValidationTest extends TestCase
{
    public function test_recommended_four_by_three_image_is_accepted(): void
    {
        $validator = Validator::make([
            'image' => UploadedFile::fake()->image('product.jpg', 1600, 1200),
        ], [
            'image' => [new ValidCmsImage(1200, 900, 4 / 3, '4:3')],
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_small_or_wrong_ratio_image_is_rejected(): void
    {
        $small = Validator::make([
            'image' => UploadedFile::fake()->image('small.jpg', 800, 600),
        ], [
            'image' => [new ValidCmsImage(1200, 900, 4 / 3, '4:3')],
        ]);
        $square = Validator::make([
            'image' => UploadedFile::fake()->image('square.jpg', 1600, 1600),
        ], [
            'image' => [new ValidCmsImage(1200, 900, 4 / 3, '4:3')],
        ]);

        $this->assertTrue($small->fails());
        $this->assertTrue($square->fails());
    }
}
