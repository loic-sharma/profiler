<?php namespace Profiler\Timers;

class Timer {

	/**
	 * The time at which the timer was started.
	 *
	 * @var double
	 */
	protected $startTime;

	/**
	 * The elapsed time (in milliseconds).
	 *
	 * @var double
	 */
	protected $elapsedTime;

	/**
	 * Start the timer.
	 *
	 * @param  double|null  $startTime
	 * @return void
	 */
	public function __construct($startTime = null)
	{
		$this->startTime = $startTime ?: microtime(true);
		$this->elapsedTime = 0;
	}

	/**
	 * Get the amount of time (in milliseconds) that elapsed while the timer
	 * was turned on.
	 *
	 * @return double
	 */
	public function getElapsedTime()
	{
		// Get the latest reading from the timer if the timer is still running. 
		if( ! is_null($this->startTime))
		{
			$elapsedTime = $this->formatTimeDifference($this->startTime, microtime(true));

			return $this->elapsedTime + $elapsedTime;
		}
		else
		{
			return $this->elapsedTime;
		}
	}

	/**
	 * Pause the timer. This returns the current elapsed time.
	 *
	 * @param  double|null  $currentTime
	 * @return double
	 */
	public function pause($currentTime = null)
	{
		if( ! is_null($this->startTime))
		{
			$currentTime = $currentTime ?: microtime(true);

			$this->elapsedTime += $this->formatTimeDifference($this->startTime, $currentTime);

			// We no longer need the timer's start time. If the
			// timer is resumed, a new start time will be recorded.
			$this->startTime = null;
		}

		return $this->elapsedTime;
	}

	/**
	 * Resume the timer. 
	 *
	 * @return void
	 */
	public function resume()
	{
		$this->startTime = microtime(true);
	}

	/**
	 * Find the difference between two times (in milliseconds).
	 *
	 * @param  double  $first
	 * @param  double  $second
	 * @return double
	 */
	protected function formatTimeDifference($first, $second)
	{
		return round(1000 * ($second - $first), 4);
	}
}