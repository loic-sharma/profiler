<?php

$app['router']->get('/_profiler/enable/{password?}', function($password = null) use ($app, $provider)
{
	$config = $app['config'];
	$passwordRequired = in_array($app['env'], $config->get('profiler::require_password'));

	if( ! $passwordRequired or ($passwordRequired and $password === $config->get('profiler::password')))
	{
		$app['session']->put($provider::SESSION_HASH, true);
	}

	return $app['redirect']->to('/');
});

$app['router']->get('/_profiler/disable', function() use ($app, $provider)
{
	$app['session']->put($provider::SESSION_HASH, false);

	return $app['redirect']->to('/');
});

$app['router']->get('/_profiler/reset', function() use ($app, $provider)
{
	$app['session']->forget($provider::SESSION_HASH);

	return $app['redirect']->to('/');
});