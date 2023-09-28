<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // 初期設定
            // Route::middleware('web')
            //     ->group(base_path('routes/web.php'));

            // 利用者側のパス
            Route::prefix('/shop')
                ->as('shop.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/user.php'));

            // 管理者側のパス
            Route::prefix('admin')
                ->as('admin.')
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/admin.php'));
        });
    }
}
