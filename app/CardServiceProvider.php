<?php

namespace App;

use Silex\Application;
use Silex\ServiceProviderInterface;

class CardServiceProvider implements ServiceProviderInterface {
	public function register(Application $app) {

		$app['card'] = $app->protect(function ($hash) use ($app) {
			$sql = "SELECT * FROM card WHERE hash = ?";
			$card = $app['db']->fetchAssoc($sql, array((string) $hash));

			return $card;
		});

		$app['movie'] = $app->protect(function ($hash) use ($app) {

			$filename = '/movies/'.$hash.".mp4";
			$filepath = constant('ROOT').$filename;

			if ( file_exists($filepath) ) {
				$app['db']->update('card', array('status' => '1'), array('hash' => (string) $hash));
				return $filename;
			}
			else {
				$app['db']->update('card', array('status' => '2'), array('hash' => (string) $hash));
				return '/waiting.mp4';
			}

		});

		$app['create_movie'] = $app->protect(function ($hash) use ($app) {

			$card = $app['card']((string) $hash);

  			$app['monolog']->addInfo($app['words']);

			$body = $card['body'];

		});


	}

	public function boot(Application $app) {

	}
}
