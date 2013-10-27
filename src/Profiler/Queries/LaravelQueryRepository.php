<?php namespace Profiler\Queries;

use Illuminate\Database\DatabaseManager;

class LaravelQueryRepository extends QueryRepository {

	/**
	 * Laravel's Database manager.
	 *
	 * @var \Illuminate\Database\DatabaseManager
	 */
	protected $database;

	/**
	 * The recorded queries.
	 *
	 * @var array
	 */
	protected $queries = array();

	/**
	 * Create a new instance.
	 *
	 * @param  \Illuminate\Database\DatabaseManager
	 * @return void
	 */
	public function __construct(DatabaseManager $database)
	{
		$this->database = $database;
	}

	/**
	 * Prepare a Laravel query and record it.
	 *
	 * @param  string  $query
	 * @param  array  $bindings
	 * @param  int  $time
	 * @param  string $connectionName
	 */
	public function recordLaravelQuery($query, $bindings, $time, $connectionName)
	{
		// If the query had some bindings we'll need to add those back
		// into the query.
		if( ! empty($bindings))
		{
			// Let's grab the query's connection. We will use it to prepare and then quote
			// the bindings before they are inserted back into the query.
			$connection = $this->database->connection($connectionName);
			$pdo = $connection->getPdo();

			$bindings = $connection->prepareBindings($bindings);

			// Let's loop add each binding back into the original query, one binding
			// at a time.
			foreach($bindings as $binding)
			{
				$query = preg_replace('/\?/', $pdo->quote($binding), $query, 1);
			}
		}

		$this->record($query, $time);
	}
}