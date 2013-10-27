<?php namespace Profiler\Logs;

use Illuminate\Events\Dispatcher;

class QueryEventHandler {

	/**
	 * The profiler's instance.
	 *
	 * @var Profiler\Profiler
	 */
	protected $profiler;

	/**
	 * The Logger's Decorator.
	 *
	 * @var \Profiler\Queries\LaravelQueryRepository
	 */
	protected $queries;

	/**
	 * Create a new instance of the Profiler's Event Handler.
	 *
	 * @param  \Profiler\Profiler $profiler
	 * @param  \Illuminate\Database\DatabaseManager $database
	 * @return void
	 */
	public function __construct(Profiler $profiler, LaravelQueryRepository $queries)
	{
		$this->profiler = $profiler;
		$this->queries = $queries;
	}

	/**
	 * Register's the event handler's events.
	 *
	 * @param  \Illuminate\Events\Dispatcher $events
	 * @return void
	 */
	public function subscribe(Dispatcher $events)
	{
        $events->listen('illuminate.query', 'Profiler\ProfilerEventHandler@onDatabaseQuery');
	}

	/**
	 * Record a Laravel query if the profiler is enabled.
	 *
	 * @param  string  $query
	 * @param  array  $bindings
	 * @param  int  $time
	 * @param  string $connectionName
	 */
	public function onDatabaseQuery($query, $bindings, $time, $connectionName)
	{
		// Don't log the query if the profiler is disabled.
		if($this->profiler->isEnabled())
		{
			$this->queries->recordLaravelQuery($query, $bindings, $time, $connectionName);
		}
	}
}