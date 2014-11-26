<?php

use Doctrine\DBAL\DriverManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

$console = new Application('Silex - Kitchen Edition', '0.1');

$app->boot();

$console
	->register('gomorra:create_movie')
	->setDescription('Creates card movies')
	->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

		$sql = "SELECT * FROM card WHERE status = 0 ORDER BY cdate ASC LIMIT 0, 5";
		$cards = $app['db']->fetchAssoc($sql);



		return $error ? 1 : 0;
	});

return $console;

