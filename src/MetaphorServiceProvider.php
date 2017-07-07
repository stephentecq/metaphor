<?php

namespace Mustaard\Metaphor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use View;
use Carbon\Carbon;

class MetaphorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        require __DIR__.'/routes/routes.php';
        $this->loadViewsFrom(__DIR__. '/views', 'Metaphor' );
        $this->loadMigrationsFrom(__DIR__.'/migrations');


        $this->publishes([
            __DIR__.'/migrations/' => database_path('migrations')
        ], 'migrations');


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('metaphor', function(){
            return new Metaphor();
        });
    }
}
