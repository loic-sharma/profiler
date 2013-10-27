<?php namespace Profiler\Primers;

use Profiler\Profiler;

interface PrimerInterface {

	/**
	 * Prepare the profiler.
	 *
	 * @param  \Profiler\Profiler  $profiler
	 * @return void
	 */
	public function prime(Profiler $profiler);
}