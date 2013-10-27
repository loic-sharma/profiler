<?php namespace Profiler\Primers;

use Profiler\Profiler;

use Illuminate\Config\Repository as Config;
use Illuminate\Session\SessionManager as Session;

class ConfigPrimer implements PrimerInterface {

	/**
	 * The configuration repository.
	 *
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * The session manager.
	 *
	 * @var \Illuminate\Session\SessionManager
	 */
	protected $session;

	/**
	 * Create a new session primer for the profiler.
	 *
	 * @param  \Illuminate\Config\Repository  $config
	 * @param  \Illuminate\Session\SessionManager  $session
	 */
	public function __construct(Config $config, Session $session)
	{
		$this->config = $config;
		$this->session = $session;
	}

	/**
	 * Enable the profiler based off of the configuration.
	 *
	 * @param  \Profiler\Profiler  $profiler
	 * @return void
	 */
	public function prime(Profiler $profiler)
	{
		$sessionKey = $this->config->get('profiler::session.key', null);

		if( ! is_null($sessionKey))
		{
			if($this->session->has($sessionKey))
			{
				$enabled = $this->session->get($sessionKey);

				$profiler->enable($enabled);
			}
		}
	}
}