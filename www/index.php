<?php

/**
 * CMS for czech minecraft server "Herocraft" created in PHP with Nette
 *
 * @author MilanT
 * @see https://github.com/MilanTuryna
 */

declare(strict_types=1);

//if(!in_array($_SERVER["REMOTE_ADDR"], [])) die("Omlouvame se, ale momentalne probiha udrzba webu.");

$_SERVER["SERVER_ADMIN"] !== "info@active24.cz" ? require __DIR__ . '/../vendor/autoload.php' : require __DIR__ . '/../home/vendor/autoload.php';

App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
