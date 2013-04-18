<?php

return array(
	
	/*
	| -----------------------------------------------------------------------------
	| Enable profiler
	| -----------------------------------------------------------------------------
	|
	| If this option is set to TRUE profiler will stay enabled even after
	| disabling it through URL (/_profiler/disable)
	|
	*/

	'enabled' => null,

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
