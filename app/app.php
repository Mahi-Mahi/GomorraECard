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


$app->register(new FFMpeg\FFMpegServiceProvider(), array(
			    'ffmpeg.configuration' => array(
			        'ffmpeg.threads'   => 4,
			        'ffmpeg.timeout'   => 300,
			        'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
			        'ffprobe.timeout'  => 30,
			        'ffprobe.binaries' => '/usr/local/bin/ffprobe',
			    ),
			    'ffmpeg.logger' => $logger,
			));

use App\ConcatVideoFilterServiceProvider;
require __DIR__.'/ConcatVideoFilterServiceProvider.php';

error_log(print_r(class_exists('ConcatVideoFilterServiceProvider'), true));

$app->register(new App\ConcatVideoFilterServiceProvider(), array());

