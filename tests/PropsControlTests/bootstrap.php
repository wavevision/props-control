<?php declare(strict_types = 1);

use Nette\Configurator;

require __DIR__ . '/../../vendor/autoload.php';
$configurator = new Configurator();
$configurator->addConfig(
	['application' => ['mapping' => ['*' => 'Wavevision\PropsControlTests\Presenters\*Presenter']]]
);
$tempDir = __DIR__ . '/../../temp';
$configurator->setTempDirectory($tempDir);
$configurator->addParameters(['wwwDir' => $tempDir]);
return $configurator->createContainer();
