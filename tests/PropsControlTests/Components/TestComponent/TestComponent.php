<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Wavevision\PropsControl\Props;
use Wavevision\PropsControl\PropsControl;

class TestComponent extends PropsControl
{

	public const CLASS_NAME_MODIFIERS = [TestProps::BOOLEAN];

	public function render(Props $props): void
	{
		$this->template->setParameters(['undefinedProp' => $this->getMappedProp('undefinedProp')]);
		parent::render($props);
	}
}
