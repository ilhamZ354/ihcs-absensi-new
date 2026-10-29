<?php

namespace App\Services\IHCS\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SSOAuthProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //memperkenalkan SSOUserProvider ke laravel
        Auth::provider('sso', function($app, array $config) {
            return new SSOUserProvider($app['hash'], $config['model']);
        });
    }
}
