<?php
// web/index.php

define('ROOT',dirname(__DIR__));

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

require __DIR__.'/../app/app.php';

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

use Symfony\Component\HttpFoundation\Request;
$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

// ROUTES

$app->get('', function () use ($app) {

 	return $app['twig']->render('hello.twig', array(
        'name' => $name,
    ));

});

$app->get('/card/{hash}', function ($hash) use ($app) {

	$card = $app['card']((string) $hash);

	return  'Card #'.$app->escape($hash)."<br />".
			"<strong>{$card['body']}</strong>".
			"<h5>{$card['author']}</h5>".
			"<small>music : {$card['music']}</small><br />".
			"<small>status : {$card['status']}</small><br />".
			"<time>{$card['cdate']}</time><br />".
			$app['movie']($hash);

});


$app->match('/card', function (Request $request) use ($app) {
    // some default data for when the form is displayed the first time
    $data = array(
        'body' => 'Votre message',
        'author' => 'Votre nom',
        'music' => '1',
    );

    $form = $app['form.factory']->createBuilder('form', $data)
        ->add('author')
        ->add('body')
        ->add('music', 'choice', array(
            'choices' => array(1 => '1', 2 => '2'),
            'expanded' => true,
        ))
        ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
        $data = $form->getData();

        $data['hash'] = md5($data['body'].$data['author'].$data['music'].microtime());

		$app['db']->insert('card', $data);

        // redirect somewhere
        return $app->redirect('/card');
    }

    // display the form
    return $app['twig']->render('index.twig', array('form' => $form->createView()));
});

$app->run();