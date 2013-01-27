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
		// Register config file
		$this->app['config']->package('loic-sharma/profiler', __DIR__.'/../config');

		$this->registerProfiler();

		$this->registerProfilerQueryEvent();

		if ($this->app['config']->get('profiler::auto_output'))
		{
			$this->registerProfilerToOutput();
		}
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
			$startTime = null;

			// Let's use the Laravel start time if it is defined.
			if(defined('LARAVEL_START'))
			{
				$startTime = LARAVEL_START;
			}

			return new Profiler(new Logger, $startTime);
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
				// Let's prepare the bindings before we try to insert them
				// into the query.
				$bindings = $app['db']->prepareBindings($event->bindings);

				// We'll use the current connection's PDO to quote the bindings.
				$pdo = $app['db']->getPdo();

				foreach($bindings as $binding)
				{
					$query = preg_replace('/\?/', $pdo->quote($binding), $query, 1);
				}
			}

			$app['profiler']->log->query($query, $event->time);
		});
	}

	/**
	 * Register an after filter to automatically display the profiler.
	 *
	 * @return void
	 */
	public function registerProfilerToOutput()
	{
		$app = $this->app;

		$app['router']->after(function($request, $response) use($app)
		{
			$responseContent = $response->getContent();
			$profiler = $app['profiler']->render();

			// If we can find a closing HTML tag in the response, let's add the
			// profiler content inside it.
			if(($pos = strrpos($responseContent, '</html>')) !== false)
			{
				$responseContent = substr($responseContent, 0, $pos).$profiler.substr($responseContent, $pos);
			}

			// If we cannot find a closing HTML tag, we'll just append the profiler
			// at the very end of the response's content.
			else
			{
				$responseContent .= $profiler;
			}


			$response->setContent($responseContent);
		});
	}
}