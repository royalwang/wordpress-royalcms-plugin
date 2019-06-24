<?php

namespace Royalcms\Component\Corcel;

use RC_Auth;
use Royalcms\Component\Corcel\Laravel\Auth\AuthUserProvider;
use Royalcms\Component\Support\ServiceProvider;

/**
 * Class CorcelServiceProvider
 *
 * @package Royalcms\Component\Corcel\Providers\Laravel
 */
class CorcelServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->publishConfigFile();
        $this->registerAuthProvider();
    }
    
    /**
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * @return void
     */
    private function publishConfigFile()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('corcel.php'),
        ]);
    }

    /**
     * @return void
     */
    private function registerAuthProvider()
    {
        RC_Auth::extend('corcel', function ($app) {
            return new AuthUserProvider($app['config']->get('auth'));
        });
    }
}
