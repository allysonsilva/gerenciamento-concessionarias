<?php

namespace App\Http\Middleware;

use Closure;
use Sentry\State\Scope;
use Illuminate\Http\Request;
use function Sentry\configureScope as sentryConfigureScope;

/**
 * @codeCoverageIgnore
 */
class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && app()->bound('sentry')) {
            sentryConfigureScope(function (Scope $scope): void {
                $scope->setUser([
                    'id' => auth()->id(),
                    // 'email' => auth()->user()->email,
                ]);

                $scope->setTag('application.name', config('app.name'));
                $scope->setTag('environment', config('app.env'));
            });
        }

        return $next($request);
    }
}
