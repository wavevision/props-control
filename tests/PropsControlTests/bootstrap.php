<?php declare(strict_types = 1);

use Nette\Configurator;

require __DIR__ . '/../../vendor/autoload.php';
$configurator = new Configurator();
$configurator->addConfig(
	['application' => ['mapping' => ['*' => 'Wavevision\PropsControlTests\Presenters\*Presenter']]]
);
$configurator->setTempDirectory(__DIR__ . '/../../temp');
return $configurator->createContainer();
