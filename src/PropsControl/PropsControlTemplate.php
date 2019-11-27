<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;

class PropsControlTemplate extends Template
{

	/**
	 * @var ClassName
	 */
	public $className;

	/**
	 * @var string[]
	 */
	public $modifiers;

	/**
	 * @var ValidProps
	 */
	public $props;

}
