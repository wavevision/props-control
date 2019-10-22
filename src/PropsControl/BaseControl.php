<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidStateException;

abstract class BaseControl extends Control
{

	protected const DEFAULT_TEMPLATE = 'default';

	protected function createTemplate(): ITemplate
	{
		/** @var Template $template */
		$template = parent::createTemplate();
		if ($parameters = $this->getTemplateParameters()) {
			$template->setParameters($parameters);
		}
		$template->setFile($this->getTemplateFile());
		return $template;
	}

	/**
	 * @return mixed[]|null
	 */
	protected function getTemplateParameters(): ?array
	{
		return null;
	}

	private function getTemplateFile(): string
	{
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException(sprintf('Unable to get filename for "%s".', static::class));
		}
		return dirname($file) . '/templates/' . static::DEFAULT_TEMPLATE . '.latte';
	}
}
