<?php

namespace App\Providers;

use App\Models\Note;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        Route::bind('notes_uid_api', function ($uid)  {
            return $this->noteBinding($uid, 'api');
        });

        Route::bind('notes_uid_web', function ($uid)  {
            return $this->noteBinding($uid, 'web');
        });
    }

    protected function noteBinding($uid, $guard) {
        return Note::where(static function(Builder $builder) use ($uid, $guard) {
            $builder->where('user_id', auth($guard)->id());
            $builder->orWhere('public', '=', true);
            $builder->orWhereHas('sharedUsers', function (Builder $builder) use ($guard) {
                return $builder->where('user_id', auth($guard)->id());
            });
        })->where('uid', '=', $uid)->first() ?? abort(403);
    }

    /**
     *
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function () {
            return Limit::perMinute(60);
        });
    }
}
