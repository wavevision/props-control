<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Wavevision\PropsControl\PropsControl;

class TestComponent extends PropsControl
{

	public function getClassName(): string
	{
		// Optionally override to get custom CSS class (default generated from control class)
		return parent::getClassName();
	}

	/**
	 * @inheritDoc
	 */
	public function getClassNameModifiers(): array
	{
		parent::getClassNameModifiers();
		// Optionally define CSS class modifiers
		return [
			// If 'booleanValue' prop is truthy, its name will be used as CSS class modifier
			TestComponentProps::BOOLEAN_VALUE,
			// Use 'type' prop value as a modifier
			TestComponentProps::TYPE => self::USE_VALUE,
			// Define custom modifier, $modifier => callback(object $props): bool, if true modifier will be used
			'custom' => function (object $props): bool {
				// $props have been validated, we're accessing nullable prop
				if ($entity = $props->{TestComponentProps::ENTITY}) {
					return $entity->enabled;
				}
				return false;
			},
		];
	}

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
