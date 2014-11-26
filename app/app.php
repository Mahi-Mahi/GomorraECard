<?php

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/config.json"));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/words.json"));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../logs/app.log',
));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => $app['db.options']
));


use App\CardServiceProvider;
require __DIR__.'/CardServiceProvider.php';
$app->register(new App\CardServiceProvider(), array());
