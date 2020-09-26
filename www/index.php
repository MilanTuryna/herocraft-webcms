<?php

/**
 * CMS for czech minecraft server "Herocraft" created in PHP with Nette
 *
 * @author MilanT
 * @see https://github.com/MilanTuryna
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';


App\Bootstrap::boot()
	->createContainer()
	->getByType(Nette\Application\Application::class)
	->run();
