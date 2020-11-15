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
     * Method used in CLI scripts based for cron actions.
     * @return Configurator
     */
    public static function bootCli(): Configurator {
        $configurator = new Configurator;
        $configurator
            ->createRobotLoader()
            ->addDirectory(__DIR__)->register();
        $configurator->setTimeZone('Europe/Prague');
        $configurator->addConfig(__DIR__ . '/config/cli.neon');
        return $configurator;
    }

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
