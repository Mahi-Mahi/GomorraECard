<?php

$env = getenv('APP_ENV') ?: 'prod';
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/../config/$env.json"));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/'.$env.'.log',
));

use Doctrine\DBAL\Configuration;
switch($env):
	case 'prod':
	break;
	default:
		$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
		    'db.options' => array(
		        'driver'   => 'pdo_sqlite',
		        'path'     => __DIR__.'/app.db',
		    ),
		));
	break;
endswitch;

