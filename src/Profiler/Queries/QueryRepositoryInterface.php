<?php namespace Profiler\Queries;

interface QueryRepositoryInterface {

	/**
	 * Record a database query.
	 *
	 * @param  string  $query
	 * @param  int  $time
	 * @return void
	 */
	public function record($query, $time);

	/**
	 * Get the recorded queries.
	 *
	 * @return array
	 */
	public function getQueries();
}