<?php namespace Profiler\Timers;

class TimerRepository {

	/**
	 * All of the stored timers.
	 *
	 * @var array
	 */
	protected $timers = array();

	/**
	 * Start a timer.
	 *
	 * @param  string  $timer
	 * @param  mixed  $startTime
	 * @return \Profiler\Timers\TimerRepository
	 */
	public function startTimer($timer, $startTime = null)
	{
		$this->timers[$timer] = new Timer($startTime);

		return $this;
	}

	/**
	 * End a timer.
	 *
	 * @param string $timer
	 * @param mixed $endTime
	 * @return \Profiler\Profiler
	 */
	public function endTimer($timer, $endTime = null)
	{
		$this->timers[$timer]->end($endTime);

		return $this;
	}

	/**
	 * Get the amount of time that passed during a timer.
	 *
	 * @param  string  $timer
	 * @return double
	 */
	public function getElapsedTime($timer)
	{
		return $this->timers[$timer]->getElapsedTime();
	}

	/**
	 * Get a timer.
	 *
	 * @param  string  $timer
	 * @return double
	 */
	public function getTimer($timer)
	{
		return $this->timers[$timer];
	}

	/**
	 * Get all of the timers.
	 *
	 * @return array
	 */
	public function getTimers()
	{
		return $this->timers;
	}

	/**
	 * Get the current application execution time in milliseconds.
	 *
	 * @return int
	 */
	public function getLoadTime()
	{
		return $this->endTimer('application')->getElapsedTime('application');
	}
}