<?php

declare(strict_types=1);

namespace App;

use Nette\Configurator;

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

		$configurator->setDebugMode(true); // enable for your remote IP
		$configurator->enableTracy(__DIR__ . '/../log');

		$configurator->setTimeZone('Europe/Prague');
		$configurator->setTempDirectory(__DIR__ . '/../temp');

		$configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();

		$configurator->addConfig(__DIR__ . '/config/common.neon');
		$configurator->addConfig(__DIR__ . '/config/local.neon');

		return $configurator;
	}
}
