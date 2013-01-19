<?php namespace Profiler;

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
	 * Register an event to automatically log database queries.
	 *
	 * @return void
	 */
	public function registerProfilerQueryEvent()
	{
		$app = $this->app;

		$app['events']->listen('illuminate.query', function($event) use ($app)
		{
			$query = $event->query;

			// If the query had some bindings we'll need to add those back
			// in to the query.
			if( ! empty($event->bindings))
			{
				// We'll use the current connection's PDO to quote the bindings.
				$pdo = $app['db']->getPdo();

				foreach($event->bindings as $binding)
				{
					$query = preg_replace('/\?/', $pdo->quote($binding), $query, 1);
				}
			}

			$app['profiler']->log->query($query, $event->time);
		});
	}
}