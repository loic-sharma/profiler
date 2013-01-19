<?php namespace Profiler\Facades;

use Illuminate\Support\Facades\Facade;

class Profiler extends Facade {

    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logEmergency($message, array $context = array())
    {
    	static::$app['profiler']->log->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logAlert($message, array $context = array())
    {
    	static::$app['profiler']->log->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logCritical($message, array $context = array())
    {
    	static::$app['profiler']->log->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logError($message, array $context = array())
    {
    	static::$app['profiler']->log->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logWarning($message, array $context = array())
    {
    	static::$app['profiler']->log->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logNotice($message, array $context = array())
    {
    	static::$app['profiler']->log->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logInfo($message, array $context = array())
    {
    	static::$app['profiler']->log->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function logDebug($message, array $context = array())
    {
    	static::$app['profiler']->log->debug($message, $context);
    }

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'profiler'; }
}