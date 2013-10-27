<?php namespace Profiler;

use Profiler\Logger\Logger;
use Illuminate\Support\ServiceProvider;

class ProfilerServiceProvider extends ServiceProvider {

	const SESSION_HASH = '_profiler';

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->package('loic-sharma/profiler', null, __DIR__.'/../');

		$this->registerProfiler();

		$this->registerProfilerLoggerEvent();

		$this->registerProfilerQueryEvent();

		$this->registerProfilerRouting();

		$this->registerProfilerToOutput();
	}

	/**
	 * Register the profiler.
	 *
	 * @return void
	 */
	public function registerProfiler()
	{
		$sessionHash = static::SESSION_HASH;

		$this->app['profiler'] = $this->app->share(function($app) use ($sessionHash)
		{
			$startTime = null;
			$enabled = false;

			// Let's use the Laravel start time if it is defined.
			if(defined('LARAVEL_START'))
			{
				$startTime = LARAVEL_START;
			}

			// Check the session to see if the profiler has been manually enabled
			// or disabled through the session. Note that the session completely overrides
			// the configuration.
			if($app['session']->has($sessionHash))
			{
				$enabled = $app['session']->get($sessionHash);
			}
			
			else
			{
				// The session contains no information as to wether the profiler is enabled
				// or not. Let's check the configurations now. 
				$enabled = $app['config']->get('profiler::enabled', null);

				// If the config is set to null (or doesn't exist), we will fallback to the application's
				// debug setting.
				if(is_null($enabled))
				{
					$enabled = $app['config']->get('app.debug');
				}
			}

			return new Profiler(new Logger, $startTime, $enabled);
		});
	}

	/**
	 * Register an event to automatically fetch Laravel's logs.
	 *
	 * @return void
	 */
	public function registerProfilerLoggerEvent()
	{
		$app = $this->app;

		$app['events']->listen('illuminate.log', function($level, $message, $context) use ($app)
		{
			if($app['profiler']->isEnabled())
			{
				$app['profiler']->log->log($level, $message, $context);
			}
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

		$app['events']->listen('illuminate.query', function($query, $bindings, $time, $connectionName) use ($app)
		{
			// Don't log the query if the profiler is disabled.
			if($app['profiler']->isEnabled())
			{
				// If the query had some bindings we'll need to add those back
				// into the query.
				if( ! empty($bindings))
				{
					// Let's grab the query's connection. We will use it to prepare and then quote
					// the bindings before they are inserted back into the query.
					$connection = $app['db']->connection($connectionName);
					$pdo = $connection->getPdo();

					$bindings = $connection->prepareBindings($bindings);

					// Let's loop add each binding back into the original query, one binding
					// at a time.
					foreach($bindings as $binding)
					{
						$query = preg_replace('/\?/', $pdo->quote($binding), $query, 1);
					}
				}

				$app['profiler']->log->query($query, $time);
			}
		});
	}

	/**
	 * Register routes to enable or disable the profiler.
	 *
	 * @return void
	 */
	public function registerProfilerRouting()
	{
		$provider = $this;

		$this->app->booting(function($app) use ($provider)
		{
			include __DIR__.'/routes.php';
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
		$sessionHash = static::SESSION_HASH;

		$app['router']->after(function($request, $response) use ($app, $sessionHash)
		{
			$profiler = $app['profiler'];
			$session = $app['session'];

			if($session->has($sessionHash))
			{
				$profiler->enable($session->get($sessionHash));
			}

			// Do not display profiler on ajax requests or non-HTML responses.
			$isHTML = \Str::startsWith($response->headers->get('Content-Type'), 'text/html');

			if( ! $profiler->isEnabled() or $request->ajax() or ! $isHTML)
			{
				return;
			}

			$responseContent = $response->getContent();

			// Don't do anything if the response content is not a string.
			if(is_string($responseContent))
			{
				$profiler = $profiler->render();

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
			}
		});
	}

}
