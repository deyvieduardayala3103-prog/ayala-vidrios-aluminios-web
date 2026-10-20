<?php

namespace App\Providers;

use App\Models\Setting;
use App\Policies\BusinessPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('manage', [BusinessPolicy::class, 'manage']);
        Gate::define('edit-content', [BusinessPolicy::class, 'editContent']);
        RateLimiter::for('public-forms', fn (Request $r) => Limit::perMinute(5)->by($r->ip()));
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(5)->by(mb_strtolower((string) $r->input('email')).'|'.$r->ip()));
        View::composer('*', function ($view) {
            if (! str_starts_with($view->name(), 'errors.')) {
                $view->with('business', Setting::pluck('value', 'key')->all());
            }
        });
    }
}
