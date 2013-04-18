<?php

return array(
	
	/*
	| -----------------------------------------------------------------------------
	| Enable profiler
	| -----------------------------------------------------------------------------
	|
	| Uncomment this option to force the profiler, regardless of app.debug
	|
	*/

	//'enabled' => true,

	/*
	| -----------------------------------------------------------------------------
	| Password for enabling profiler
	| -----------------------------------------------------------------------------
	|
	| This password is required to enable profiler on selected environments.
	| 
	| You should change it after installation.
	|
	*/

	'password' => false,

	/*
	| -----------------------------------------------------------------------------
	| Require password on selected environments
	| -----------------------------------------------------------------------------
	|
	| Profiler can be enabled by running: /_profiler/enable/{password} in browser.
	| Some environemts require password to be given before enabling.
	|
	*/

	'require_password' => array('production', 'prod'),

);
