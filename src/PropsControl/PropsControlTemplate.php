<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;
use Wavevision\PropsControl\Helpers\ClassName;
use Wavevision\PropsControl\Helpers\Style;

class PropsControlTemplate extends Template
{

	public ClassName $className;

	public Props $definition;

	/**
	 * @var string[]
	 */
	public array $modifiers;

	public ValidProps $props;

	public Style $style;

}
