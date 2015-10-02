<?php
namespace Visualplus\Board;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
	
	/**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
        	__DIR__.'/migrations/' => database_path('migrations'),
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
    }
}