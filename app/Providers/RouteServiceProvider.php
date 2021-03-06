<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use App\Models\PayType;
use App\Models\Prescription;
use App\Models\Product;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api/v1')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        Route::model('user', User::class);
        Route::model('doctor', User::class);
        Route::model('pharmacist', User::class);
        Route::model('pharmacist', User::class);
        Route::model('prescription', Prescription::class);
        Route::model('branch', Branch::class);
        Route::model('product', Product::class);
        Route::model('category', Category::class);
        Route::model('order', Order::class);
        Route::model('pay_type', PayType::class);
        Route::bind('my_notification', function ($id) {
            return auth()->user()->notifications()->whereKey($id)->firstOrFail();
        });
        Route::model('notification', \Illuminate\Notifications\DatabaseNotification::class);

    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
