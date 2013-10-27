<?php namespace Profiler\Logs;

use Psr\Log\LoggerInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class LoggerDecorator extends AbstractLogger {

	/**
	 * The logger that this object decorates.
	 *
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * The logs recorded to the logger.
	 *
	 * @var array
	 */
	protected $logs = array();

	/**
	 * Create a new instance of the LoggerDecorator.
	 *
	 * @param  \Psr\Log\LoggerInterface
	 * @return void
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log($level, $message, array $context = array())
	{
		$this->logs[] = compact('level', 'message', 'context');

		$this->notifyLogger($level, $message, $context);
	}

	/**
	 * Get the recorded logs.
	 *
	 * @return array
	 */
	public function getLogs()
	{
		return $this->logs;
	}

	/**
	 * Notify the logger know about the new log.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return void
	 */
	protected function notifyLogger($level, $message, array $context = array())
	{
		switch($level)
		{
			case LogLevel::EMERGENCY:
				$this->logger->emergency($message, $context);
				break;

			case LogLevel::ALERT:
				$this->logger->alert($message, $context);
				break;

			case LogLevel::CRITICAL:
				$this->logger->critical($message, $context);
				break;

			case LogLevel::ERROR:
				$this->logger->error($message, $context);
				break;

			case LogLevel::WARNING:
				$this->logger->warning($message, $context);
				break;

			case LogLevel::NOTICE:
				$this->logger->notice($message, $context);
				break;

			case LogLevel::INFO:
				$this->logger->info($message, $context);
				break;

			case LogLevel::DEBUG:
				$this->logger->debug($message, $context);
				break;

			default:
				throw new \InvalidArgumentException;
				break;
		}
	}
}