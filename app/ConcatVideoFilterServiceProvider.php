<?php

namespace App;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ConcatVideoFilterServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['video_concat_filter'] = $app->protect(function ($name) use ($app) {

            return array();

            // return new ConcatVideoFilter();

        });
    }

    public function boot(Application $app)
    {
    }
}