<?php namespace Profiler;

use Profiler\Logger\Logger;
use Illuminate\Support\ServiceProvider;

class ProfilerServiceProvider extends ServiceProvider {

	const SESSION_HASH = '_profiler';

	public function boot()
	{
		$this->package('loic-sharma/profiler', null, __DIR__.'/../');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerProfiler();

		$this->registerProfilerQueryEvent();

		$this->registerProfilerToOutput();

		$this->registerRouting();
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

			// Let's see if the profiler is enabled. If the config is set to null, or
			// if the config is not found, we will fallback to the application's
			// debug setting.
			$enabled = $app['config']->get('profiler::enabled', null);

			if(is_null($enabled))
			{
				$enabled = $app['config']->get('app.debug');
			}

			return new Profiler(new Logger, $startTime, $enabled);
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

		$app['events']->listen('illuminate.query', function($query, $bindings, $time) use ($app)
		{
			// If the query had some bindings we'll need to add those back
			// in to the query.
			if( ! empty($bindings))
			{
				// Let's prepare the bindings before we try to insert them
				// into the query.
				$bindings = $app['db']->prepareBindings($bindings);

				// We'll use the current connection's PDO to quote the bindings.
				$pdo = $app['db']->getPdo();

				foreach($bindings as $binding)
				{
					$query = preg_replace('/\?/', $pdo->quote($binding), $query, 1);
				}
			}

			$app['profiler']->log->query($query, $time);
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
		$session_hash = static::SESSION_HASH;

		$app['router']->after(function($request, $response) use ($app, $session_hash)
		{
			$profiler = $app['profiler'];
			$session = $app['session'];

			if($session->has($session_hash))
			{
				$profiler->enable($session->get($session_hash));
			}

			// Do not display profiler on ajax requests or non-HTML responses.
			$isHTML = \Str::startsWith($response->headers->get('Content-Type'), 'text/html');

			if( ! $profiler->isEnabled() or $request->ajax() or ! $isHTML)
			{
				return;
			}

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

	/**
	 * Register routes to enable or disable the profiler.
	 *
	 * @return void
	 */
	public function registerRouting()
	{
		$provider = $this;

		$this->app->booting(function($app) use ($provider)
		{
			$app['router']->get('/_profiler/enable/{password?}', function($password = null) use ($app, $provider)
			{
				$config = $app['config'];
				$password_required = in_array($app['env'], $config->get('profiler::require_password'));

				if( ! $password_required or ($password_required and $password === $config->get('profiler::password')))
				{
					$app['session']->put($provider::SESSION_HASH, true);
				}

				return $app['redirect']->to('/');
			});

			$app['router']->get('/_profiler/disable', function() use ($app, $provider)
			{
				$app['session']->put($provider::SESSION_HASH, false);

				return $app['redirect']->to('/');
			});
			
			$app['router']->get('/_profiler/reset', function() use ($app, $provider)
			{
				$app['session']->forget($provider::SESSION_HASH);

				return $app['redirect']->to('/');
			});
		});
	}
}
