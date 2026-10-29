<?php

namespace App\Services\IHCS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Services\IHCS\Auth\SSOServiceGuard;

class SSOServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::extend('SSOServiceGuard', function($app) {
            $provider = new SSOUserProvider($app['hash'], config('auth.providers.users.model'));
            return new SSOServiceGuard('SSOServiceGuard', $provider, app()->make('session.store'), request());
        });
    }
}
