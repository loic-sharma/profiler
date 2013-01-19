<?php

use Profiler\Profiler;
use Profiler\Logger\Logger;

use Illuminate\Support\ServiceProvider;

class ProfilerServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProfiler();

		$this->registerProfilerQueryEvent();
	}

	/**
	 * Register the profiler.
	 *
	 * @return void
	 */
	public function registerProfiler()
	{	
		$this->app['profiler'] = $this->app->share(function($app)
		{
			return new Profiler(new Logger, LARAVEL_START);
		});
	}

	/**
	 * Register a Laravel event to automatically track database queries.
	 *
	 * @return void
	 */
	public function registerProfilerQueryEvent()
	{
		$app = $this->app;

		$app['event']->listen('illuminate.query', function($query) use ($app)
		{
			$query = $query['query'];

			foreach($query['bindings'] as $binding)
			{
				$query = preg_replace('/?/', $binding, $query, 1);
			}

			$app['profiler']->log->query($query, $query['time']);
		});
	}
}