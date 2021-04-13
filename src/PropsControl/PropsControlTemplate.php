<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Wavevision\PropsControl\Exceptions\NotAllowed;
use Wavevision\PropsControl\Helpers\ClassName;
use Wavevision\PropsControl\Helpers\Style;
use Wavevision\Utils\Arrays;
use function property_exists;

class PropsControlTemplate extends Template
{

	/**
	 * @var string[]
	 */
	public array $modifiers;

	public ClassName $className;

	public Presenter $presenter;

	public Props $definition;

	public PropsControl $control;

	public string $basePath;

	public string $baseUrl;

	public Style $style;

	public ValidProps $props;

	/**
	 * @param mixed $value
	 */
	public function add(string $name, $value): self
	{
		if (property_exists($this, $name)) {
			throw new NotAllowed("The variable '$name' already exists.");
		}
		$this->$name = $value;
		return $this;
	}

	/**
	 * @param array<mixed> $parameters
	 */
	public function setParameters(array $parameters): self
	{
		/** @var self $template */
		$template = Arrays::toObject($parameters, $this);
		return $template;
	}

}
