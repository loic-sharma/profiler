<?php namespace Profiler\Queries;

class QueryRepository implements QueryRepositoryInterface {

	/**
	 * The recorded queries.
	 *
	 * @var array
	 */
	protected $queries = array();

	/**
	 * Record a database query.
	 *
	 * @param  string  $query
	 * @param  int  $time
	 * @return void
	 */
	public function record($query, $time)
	{
		$this->queries[] = compact($query, $time);
	}

	/**
	 * Get the recorded queries.
	 *
	 * @return array
	 */
	public function getQueries()
	{
		return $this->queries;
	}
}