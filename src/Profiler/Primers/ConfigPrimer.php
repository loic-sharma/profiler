<?php namespace Profiler\Primers;

use Profiler\Profiler;
use Illuminate\Config\Repository as Config;

class ConfigPrimer {

	/**
	 * The configuration repository.
	 *
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * Create a new config primer for the profiler.
	 *
	 * @param  \Illuminate\Config\Repository  $config
	 */
	public function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * Enable the profiler based off of the configuration.
	 *
	 * @param  \Profiler\Profiler  $profiler
	 * @return void
	 */
	public function prime(Profiler $profiler)
	{
		$enabled = $this->config->get('profiler::enabled', null);

		// If the config is null, fallback to the application's debug state.
		if(is_null($enabled))
		{
			$enabled = $this->config->get('app.debug', false);
		}

		$profiler->enabled($enabled);
	}
}