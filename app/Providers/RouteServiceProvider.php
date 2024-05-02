<?php

namespace App\Providers;

use Illuminate\Support\Facades\{
    Route,
    RateLimiter,
};

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware(['api', 'auth', 'throttle:api'])
                ->name('api.')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['api'])
                ->prefix('auth')
                ->name('auth.')
                ->group(base_path('routes/auth.php'));

            Route::middleware('web')->name('web.')->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('global', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(180)->by($request->user()->getKey())
                : Limit::perMinute(120)->by($request->ip());
        });

        RateLimiter::for('safe', function (Request $request) {
            return Limit::perSecond(6)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(120)->by($request->user()->getKey())
                : Limit::perMinute(60)->by($request->ip());
        });
    }
}
