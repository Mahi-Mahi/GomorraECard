<?php

namespace App;

use Silex\Application;
use Silex\ServiceProviderInterface;

/*

status :

	0  =>	empty
	1  =>   ready
	2  =>	waiting

*/

class CardServiceProvider implements ServiceProviderInterface {
	public function register(Application $app) {

		$app['card'] = $app->protect(function ($hash) use ($app) {
			$sql = "SELECT * FROM card WHERE hash = ?";
			$card = $app['db']->fetchAssoc($sql, array((string) $hash));

			return $card;
		});

		$app['movie'] = $app->protect(function ($hash) use ($app) {

			$filename = '/movies/'.$hash."/video.mp4";
			$filepath = constant('ROOT').'/web/'.$filename;

			if ( false && file_exists($filepath) ) {
				$app['db']->update('card', array('status' => '1'), array('hash' => (string) $hash));
				return $filename;
			}
			else {
				$app['db']->update('card', array('status' => '2'), array('hash' => (string) $hash));
				$app['create_movie']($hash);
				return '/waiting.mp4';
			}

		});

		$app['create_movie'] = $app->protect(function ($hash) use ($app) {

			$card = $app['card']((string) $hash);

			// handle easter eggs

			$msg = preg_split("# #", $card['body']);

			$video = array();
			foreach($msg as $L):
				$w = $app['words'][$L];
				// $w['v'] = constant('ROOT').'/videos_src/'.$L.'.mp4';
				$w['v'] = 'videos_src/'.$L.'.mp4';
				$video[] = $w;
			endforeach;

			// error_log(print_r($video, true));

			$filename = '/movies/'.$hash."/video.mp4";
			$filepath = constant('ROOT').'/web/'.$filename;

			if ( ! is_dir(dirname($filepath)))
				mkdir(dirname($filepath));

			$log_file = 'web/movies/'.$hash.'/res.log';
			if ( is_file(constant('ROOT').'/'.$log_file) )
				unlink(constant('ROOT').'/'.$log_file);

			if ( is_file(constant('ROOT').'/web'.$filename) )
				unlink(constant('ROOT').'/web'.$filename);

			$video = $app['ffmpeg']->open(constant('ROOT').'/videos_src/_.mp4');


			// $video_parts = array();
			// $subtitles = array();
			// foreach($video as $part):
			// 	// $video_parts[] = "file '".$part['v']."'";
			// 	$video_parts[] = $part['v'];
			// 	$concat_filter->addFile($part['v']);
			// endforeach;

			// $format = new FFMpeg\Format\Video\Mp4();
			// $video->addFilter($concat_filter);
			// $video->save($format, $filepath);

			/*

			error_log(print_r($video_parts, true));

			$parts_file = 'web/movies/'.$hash.'/parts.txt';
			file_put_contents(constant('ROOT').'/'.$parts_file, implode("\n", $video_parts));

			// $concat_cmd = 'ffmpeg -f concat -i '.$parts_file.' -c copy web'.$filename.' > '.$log_file.' 2>&1';

			$concat_cmd = 'ffmpeg -i "concat:'.implode('|', $video_parts).'" -c copy web'.$filename.' > '.$log_file.' 2>&1';

			$concat_cmd = "touch ".$log_file;

			error_log($concat_cmd);

			shell_exec($concat_cmd);

			if ( is_file(constant('ROOT').'/'.$log_file) )
				error_log(file_get_contents(constant('ROOT').'/'.$log_file));

			*/

		});

	}

	public function boot(Application $app) {

	}
}
