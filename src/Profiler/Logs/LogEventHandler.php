<?php namespace Profiler\Logs;

use Illuminate\Events\Dispatcher;

class LogEventHandler {

	/**
	 * The profiler's instance.
	 *
	 * @var Profiler\Profiler
	 */
	protected $profiler;

	/**
	 * The Logger's Decorator.
	 *
	 * @var \Profiler\Logs\LoggerDecorator
	 */
	protected $logger;

	/**
	 * Create a new instance of the Profiler's Event Handler.
	 *
	 * @param  \Profiler\Profiler $profiler
	 * @param  \Illuminate\Database\DatabaseManager $database
	 * @return void
	 */
	public function __construct(Profiler $profiler, LoggerDecorator $logger)
	{
		$this->profiler = $profiler;
		$this->logger = $logger;
	}

	/**
	 * Register's the event handler's events.
	 *
	 * @param  \Illuminate\Events\Dispatcher $events
	 * @return void
	 */
	public function subscribe(Dispatcher $events)
	{
        $events->listen('illuminate.log', 'Profiler\Logs\LogEventHandler@onLog');
	}

	/**
	 * Notify the Logger's Decorator of the new log if the profiler is
	 * currently turned on.
	 *
	 * @param  mixed   $level
	 * @param  string  $message
	 * @param  array   $context
	 * @return void
	 */
	public function onLog($level, $message, $context)
	{
		// Only record the log if the profiler is enabled.
		if($this->profiler->isEnabled())
		{
			$this->logger->log($level, $message, $context);
		}
		else
		{
			$this->logger->notifyLogger($level, $message, $context);
		}
	}
}