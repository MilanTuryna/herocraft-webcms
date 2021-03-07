<?php

declare(strict_types=1);

namespace App;

use Nette\Bootstrap\Configurator;
use Tracy\Debugger;

/**
 * Class Bootstrap
 * @package App
 */
class Bootstrap
{

    /**
     * Default method for boot in index.php
     * @return Configurator
     */
	public static function boot(): Configurator
	{
	    $configurator = new Configurator;

		$configurator->setDebugMode(true);
		$configurator->enableTracy(__DIR__ . '/../log');

        Debugger::$strictMode = E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED & ~E_USER_NOTICE;

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(__DIR__ . '/config/application.neon');
		$configurator->addConfig(__DIR__ . '/config/extensions.neon.neon');
        $configurator->addConfig(__DIR__ . '/config/database1.neon');
		$configurator->addConfig(__DIR__ . '/config/loader.neon');
		$configurator->addConfig(__DIR__ . '/config/services.neon');

		return $configurator;
	}
}
