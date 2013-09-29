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
	 * @var \Psr\Log\LoggerInterface
	 */
	public $log;

	/**
	 * All of the stored timers.
	 *
	 * @var array
	 */
	protected $timers = array();

	
	protected $topicsInfos;
	
	protected $view;
	

	/**
	 * Register the logger and application start time.
	 *
	 * @param  \Psr\Log\LoggerInterface  $logger
	 * @param  View  $view
	 * @param  mixed  $startTime
	 * @param  bool  $on
	 * @return void
	 */
	public function __construct(LoggerInterface $logger, $view, $startTime = null, $on = true)
	{
		$this->setLogger($logger);
		$this->view = $view;
		$this->startTimer('application', $startTime);
		$this->enable($on);
		
		//$topics=\Config::get('profiler::topics');
		$this->topicsInfos= new \stdClass;
		$xmlConf = simplexml_load_file( __DIR__.'/../config/config.xml' );
		foreach($xmlConf->topics->topic as $topic){
			$object='\\Profiler\\Topics\\'.ucfirst((String)$topic);
			$this->topicsInfos->{$topic}= new $object((String)$topic,$topic['btn']);
		}
	}
	

	/**
	 * Set the logger.
	 *
	 * @param  \Psr\Log\LoggerInterface  $logger
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
	 * @param  string  $timer
	 * @param  mixed  $startTime
	 * @return \Profiler\Profiler
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
	 * A helper to convert a size into a readable format
	 *
	 * @param  int     $size
	 * @param  string  $format
	 * @return string
	 */
	static public function readableSize($size, $format = null)
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
			$this->queryTopics();
			
			$profiler = $this;
			$logger = $this->log;
			$assetPath = __DIR__.'/../../assets/';

			ob_start();

			include __DIR__ .'/../../views/profiler.php';

			return ob_get_clean();
		}
	}
	
	private function queryTopics()
	{
		if(!$this->enabled) return;
		foreach ($this->topicsInfos as $topic)  {
			$topic->query();
		}
	}	
	public function renderTopics()
	{
		if(!$this->enabled) return;
		$result='';
		foreach ($this->topicsInfos as $topic)  {
			$result.= $topic->render($this->view);
		}
		return $result;
	}
	public function renderBtns()
	{
		if(!$this->enabled) return;
		$result='';
		foreach ($this->topicsInfos as $topic)  {
			$result.=$topic->renderBtn();
		}
		return $result;
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
