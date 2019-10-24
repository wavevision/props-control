<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Wavevision\PropsControl\PropsControl;

class TestComponent extends PropsControl
{

	/**
	 * Optionally override default CSS class generated from component class
	 * public const CLASS_NAME = 'my-class';
	 */
	/**
	 * Choose which props behave as CSS class modifiers if they are truthy
	 * If you want a prop's value to behave as modifier, use $prop => true
	 */
	public const CLASS_NAME_MODIFIERS = [TestComponentProps::BOOLEAN_VALUE, TestComponentProps::TYPE => true];

	protected function beforeMapPropsToTemplate(object $props): void
	{
		parent::beforeMapPropsToTemplate($props);
		// do stuff before valid props are sent to template, e.g. assign extra params to template
		$this->template->setParameters(['undefinedProp' => $this->getMappedProp('undefinedProp')]);
	}

	protected function beforeRender(object $props): void
	{
		parent::beforeRender($props);
		// do stuff before component is rendered
	}

	protected function getPropsClass(): string
	{
		return TestComponentProps::class;
	}
}
