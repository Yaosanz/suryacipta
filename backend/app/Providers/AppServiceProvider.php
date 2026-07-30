<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ActivityPolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Activity::class, ActivityPolicy::class);

        Event::listen(Login::class, function (Login $event): void {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });

        RateLimiter::for('inquiries', function (Request $request): array {
            $email = mb_strtolower((string) $request->input('email', 'anonymous'));
            $key = hash('sha256', $request->ip().'|'.$email);

            return [
                Limit::perMinute(3)->by($key),
                Limit::perHour(10)->by($key),
            ];
        });

        RateLimiter::for('public-content', fn (Request $request) => [
            Limit::perMinute(120)->by($request->ip()),
        ]);
    }
}
