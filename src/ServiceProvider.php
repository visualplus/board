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
		
		$this->publishes([
        	__DIR__.'/assets/' => public_path('visualplus/assets'),
        ], 'public');
		
		// 패키지 라우팅 설정 
		if (! $this->app->routesAreCached()) {
	        require __DIR__.'/routes.php';
	    }
		
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