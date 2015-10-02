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
		
		// 기본 스킨을 위한 view 경로 지정.
		$this->loadViewsFrom(__DIR__.'/views', 'board');
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