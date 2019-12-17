<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;

class PropsControlTemplate extends Template
{

	public ClassName $className;

	/**
	 * @var string[]
	 */
	public array $modifiers;

	public ValidProps $props;

}
