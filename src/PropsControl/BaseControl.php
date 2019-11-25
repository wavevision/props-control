<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\InvalidStateException;

/**
 * @property-read Template $template
 */
abstract class BaseControl extends Control
{

	protected const DEFAULT_TEMPLATE = 'default';

	/**
	 * @var callable[]
	 */
	private $onCreateTemplate = [];

	final protected function onCreateTemplate(callable $callback): self
	{
		$this->onCreateTemplate[] = $callback;
		return $this;
	}

	protected function createTemplate(): ITemplate
	{
		/** @var Template $template */
		$template = parent::createTemplate();
		foreach ($this->onCreateTemplate as $callback) {
			$callback($template);
		}
		$template->setFile($this->getTemplateFile());
		return $template;
	}

	final protected function getTemplateFile(?string $template = null): string
	{
		if (!$template) {
			$template = static::DEFAULT_TEMPLATE;
		}
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException(sprintf('Unable to get filename for "%s".', static::class));
		}
		return dirname($file) . "/templates/$template.latte";
	}
}
