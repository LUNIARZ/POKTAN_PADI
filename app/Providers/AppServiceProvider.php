<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        DB::prohibitDestructiveCommands($this->app->isProduction());

        if ($this->app->isProduction() && Str::startsWith((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('login', function (Request $request) {
            $identifier = Str::lower(trim((string) $request->input('username')));

            return [
                Limit::perMinute(20)->by($request->ip()),
                Limit::perMinute(5)->by($identifier.'|'.$request->ip()),
            ];
        });

        RateLimiter::for('registration', fn (Request $request) => [
            Limit::perHour(20)->by($request->ip()),
            Limit::perMinute(3)->by($request->ip()),
        ]);

        RateLimiter::for('weather', fn (Request $request) => Limit::perMinute(30)
            ->by((string) ($request->user()?->id ?? $request->ip())));

        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)
            ->by((string) ($request->user()?->id ?? $request->ip())));
    }
}
