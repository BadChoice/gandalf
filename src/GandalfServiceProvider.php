<?php namespace BadChoice\Gandalf;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class GandalfServiceProvider extends ServiceProvider
{
    //protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../database/migrations/' => base_path('database/migrations'),
        ], 'migrations');

        AliasLoader::getInstance()->alias('Agent', 'Jenssegers\Agent\Facades\Agent');
        AliasLoader::getInstance()->alias('Location','Stevebauman\Location\Facades\Location');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register('Jenssegers\Agent\AgentServiceProvider');
        $this->app->register('Stevebauman\Location\LocationServiceProvider');
    }
}
