<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Configurator;
use Nette\DI\Container;

class Bootstrap
{

	/**
	 * @var Container
	 */
	private static $container;

	public static function boot(): void
	{
		$configurator = new Configurator();
		$configurator->addConfig(
			['application' => ['mapping' => ['*' => 'Wavevision\PropsControlTests\Presenters\*Presenter']]]
		);
		$tempDir = __DIR__ . '/../../temp';
		$configurator->setTempDirectory($tempDir);
		$configurator->addParameters(['wwwDir' => $tempDir]);
		self::$container = $configurator->createContainer();
	}

	public static function getContainer(): Container
	{
		return self::$container;
	}
}
