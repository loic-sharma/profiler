<?php

$router->get('/_profiler/enable/{password?}', function($password = null) use ($config, $session, $redirect)
{
	$passwordRequired = in_array($app['env'], $config->get('profiler::session.environments'));

	if( ! $passwordRequired or ($password === $config->get('profiler::session.routes.password')))
	{
		$session->put($config->get('profiler::session.key'), true);
	}

	return $redirect->to('/');
});

$router->get('/_profiler/disable', function() use ($config, $session, $redirect)
{
	$session->put($config->get('provider::sesssion.key'), false);

	return $redirect->to('/');
});

$router->get('/_profiler/reset', function() use ($config, $session, $redirect)
{
	$session->forget($config->get('provider::session.key'));

	return $redirect->to('/');
});