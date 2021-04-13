<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\Template;
use function dirname;

/**
 * @property-read Template $template
 */
abstract class BaseControl extends Control
{

	protected const DEFAULT_TEMPLATE = 'default';

	/**
	 * @var callable[]
	 */
	private array $onCreateTemplate = [];

	protected function createTemplate(): Template
	{
		$template = parent::createTemplate();
		foreach ($this->onCreateTemplate as $callback) {
			$callback($template);
		}
		$template->setFile($this->getTemplateFile());
		return $template;
	}

	/**
	 * @param callable(Template): void $callback
	 */
	final protected function onCreateTemplate(callable $callback): self
	{
		$this->onCreateTemplate[] = $callback;
		return $this;
	}

	final protected function getTemplateFile(?string $template = null): string
	{
		if (!$template) {
			$template = static::DEFAULT_TEMPLATE;
		}
		$file = (string)$this->getReflection()->getFileName();
		return dirname($file) . "/templates/$template.latte";
	}

}
