<?php

use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Middleware\EnsureAllowedOrigin;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Support\Facades\Route;

Route::post('/inquiries', [InquiryController::class, 'store'])
    ->middleware([
        SecurityHeaders::class,
        EnsureAllowedOrigin::class,
        'throttle:inquiries',
    ]);

Route::prefix('v1')->middleware([SecurityHeaders::class, 'throttle:public-content'])->group(function (): void {
    Route::get('/content', [PublicContentController::class, 'index']);
    Route::get('/products/{slug}', [PublicContentController::class, 'product'])
        ->where('slug', '[a-z0-9-]+');
    Route::get('/posts/{slug}', [PublicContentController::class, 'post'])
        ->where('slug', '[a-z0-9-]+');
});
