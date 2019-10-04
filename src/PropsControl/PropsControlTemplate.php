<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;

class PropsControlTemplate extends Template
{

	/**
	 * @var callable
	 */
	public $blockClass;

	/**
	 * @var callable
	 */
	public $elementClass;

	/**
	 * @var array<string>
	 */
	public $modifiers;

	/**
	 * @var object
	 */
	public $props;
}
