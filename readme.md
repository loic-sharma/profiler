# Profiler

A PHP 5.3 profiler based off of Laravel 3's Anbu.

## Installation

Installing profiler is simple. First, you'll need to add the package to the `require` attribute of your `composer.json` file.

```json
{
    "require": {
        "loic-sharma/profiler": "1.0.*"
    },
}
``` 


### Installing Using Laravel 4

To enable te profiler in Laravel 4 you will need to register the Service Provider and the Facade.

1. Add `Profiler\ProfilerServiceProvider` to the list of service providers in `app/config/app.php`
2. Add `'Profiler' => 'Profiler\Facades\Profiler',` to the list of class aliases in `app/config/app.php`
3. In console run `php artisan config:publish loic-sharma/profiler`

And voila! You can use the profiler.

```php

Profiler::logDebug($object);

Profiler::startTimer('testLogging');

Profiler::logInfo('Hello World!');
Profiler::logNotice('Some event occurred.');
Profiler::logWarning('Careful: some warning.');
Profiler::logError('Runtime error.');
Profiler::logCritical('This needs to be fixed now!');
Profiler::logEmergency('The website is down right now.');

Profiler::endTimer('testLogging');

```

### Installing For Your Own Project

Add the following to your code:

```php
$profiler = new Profiler\Profiler(new Profiler\Logger\Logger);
```

You can now use the profiler to your heart's content.

```php

$profiler->startTimer('testLogging');

$profiler->log->debug($object);

$profiler->log->info('Hello World!');
$profiler->log->notice('Some event occurred.');
$profiler->log->warning('Careful: some warning.');
$profiler->log->error('Runtime error.');
$profiler->log->critical('This needs to be fixed now!');
$profiler->log->emergency('The website is down right now.');

$profiler->endTimer('testLogging');

echo $profiler;
```

## Copyright and License

Profiler was written by Loic Sharma. Profiler is released under the 2-clause BSD License. See the LICENSE file for details.

Copyright 2012 Loic Sharma
