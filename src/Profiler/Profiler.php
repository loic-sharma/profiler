<?php namespace Profiler;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareInterface;

class Profiler implements LoggerAwareInterface {

	/**
	 * Wether the profiler is enabled or not.
	 *
	 * @var bool
	 */
	public $enabled;

	/**
	 * The logger.
	 *
	 * @var Psr\Log\LoggerInterface
	 */
	public $log;

	/**
	 * The application start time.
	 *
	 * @var int
	 */
	protected $startTime;

	/**
	 * All of the stored timers.
	 *
	 * @var array
	 */
	protected $timers = array();

	/**
	 * The included files.
	 *
	 * @var array
	 */
	protected $includedFiles = array();

	/**
	 * Register the logger and application start time.
	 *
	 * @param  Psr\Logger\LoggerInterface $logger
	 * @return void
	 */
	public function __construct(LoggerInterface $logger, $startTime = null, $on = true)
	{
		$this->setLogger($logger);
		$this->startTimer('application', $startTime);
		$this->enable($on);
	}

	/**
	 * Set the logger.
	 *
	 * @param  Psr\Logger\LoggerInterface $logger
	 * @return void
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->log = $logger;
	}

	/**
	 * Enable the profiler.
	 *
	 * @param  bool  $on
	 * @return void
	 */
	public function enable($on = true)
	{
		$this->enabled = $on;
	}

	/**
	 * Disable the profiler.
	 *
	 * @return void
	 */
	public function disable()
	{
		$this->enable(false);
	}

	/**
	 * Check if profiler is enabled
	 *
	 * @return boolean
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Start a timer.
	 *
	 * @return Profiler\Profiler
	 */
	public function startTimer($timer, $startTime = null)
	{
		if(is_null($startTime))
		{
			$startTime = microtime(true);
		}

		$this->timers[$timer]['start'] = $startTime;

		return $this;
	}

	/**
	 * End a timer.
	 *
	 * @return Profiler\Profiler
	 */
	public function endTimer($timer, $endTime = null)
	{
		if(is_null($endTime))
		{
			$endTime = microtime(true);
		}

		$this->timers[$timer]['end'] = $endTime;

		return $this;
	}

	/**
	 * Get a timer.
	 *
	 * @param  string  $timer
	 * @return double
	 */
	public function getTimer($timer)
	{
		if(isset($this->timers[$timer]))
		{
			// Turn off the timer if it hasn't been already.
			if( ! isset($this->timers[$timer]['end']))
			{
				$this->timers[$timer]['end'] = microtime(true);
			}

			$timer = $this->timers[$timer];

			return round(1000 * ($timer['end'] - $timer['start']), 4);
		}

		else
		{
			throw new Exception("Todo: InvalidArgumentException");
		}
	}

	/**
	 * Get all of the executed timers.
	 *
	 * @return array
	 */
	public function getTimers()
	{
		$timers = array();

		foreach($this->timers as $timer => $data)
		{
			$timers[$timer] = $this->getTimer($timer);
		}

		return $timers;
	}

	/**
	 * Get the current application execution time in milliseconds.
	 *
	 * @return int
	 */
	public function getLoadTime()
	{
		return $this->endTimer('application')->getTimer('application');
	}

	/**
	 * Get the current memory usage in a readable format.
	 *
	 * @return string
	 */
	public function getMemoryUsage()
	{
		return $this->readableSize(memory_get_usage(true));
	}

	/**
	 * Get the peak memory usage in a readable format.
	 *
	 * @return string
	 */
	public function getMemoryPeak()
	{
		return $this->readableSize(memory_get_peak_usage());
	}

	/**
	 * Get all of the files that have been included.
	 *
	 * @return array
	 */
	public function getIncludedFiles()
	{
		// We'll cache this internally to avoid running this
		// multiple times.
		if(empty($this->includedFiles))
		{
			$files = get_included_files();

			foreach($files as $filePath)
			{
				$size = $this->readableSize(filesize($filePath));

				$this->includedFiles[] = compact('filePath', 'size');
			}
		}

		return $this->includedFiles;
	}

	/**
	 * A helper to convert a size into a readable format
	 *
	 * @param  int     $size
	 * @param  string  $format
	 * @return string
	 */
	protected function readableSize($size, $format = null)
	{
		// adapted from code at http://aidanlister.com/repos/v/function.size_readable.php
		$sizes = array('bytes', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		if(is_null($format))
		{
			$format = '%01.2f %s';
		}

		$lastsizestring = end($sizes);

		foreach ($sizes as $sizestring)
		{
			if ($size < 1024)
			{
				break;
			}

			if ($sizestring != $lastsizestring)
			{
				$size /= 1024;
			}
		}

		// Bytes aren't normally fractional
		if($sizestring == $sizes[0])
		{
			$format = '%01d %s';
		}

		return sprintf($format, $size, $sizestring);
	}

	/**
	 * Render the profiler.
	 *
	 * @return string
	 */
	public function render()
	{
		if($this->enabled)
		{
			$profiler = $this;
			$logger = $this->log;
			$assetPath = __DIR__.'/../../assets/';

			ob_start();

			include __DIR__ .'/../../views/profiler.php';

			return ob_get_clean();
		}
	}

	/**
	 * Render the profiler.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
}
