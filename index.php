<?php

include 'vendor/autoload.php';

$profiler = new Profiler\Profiler(new Profiler\Logger\Logger);

echo $profiler;