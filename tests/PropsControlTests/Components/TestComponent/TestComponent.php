<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Wavevision\PropsControl\PropsControl;
use Wavevision\PropsControl\ValidProps;

class TestComponent extends PropsControl
{

	public function getClassName(): string
	{
		// Optionally override to get custom CSS class (default generated from control class)
		parent::getClassName();
		return 'tc';
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
			// Define custom modifier, $modifier => callback(ValidProps $props), if truthy, modifier will be used...
			'custom' => function (ValidProps $props): bool {
				// $props have been validated, we're accessing nullable prop
				if ($entity = $props->get(TestComponentProps::ENTITY)) {
					return $entity->enabled;
				}
				return false;
			},
			// ...or if a string is returned, it will be used as modifier
			function (): string {
				return 'some-other-modifier';
			},
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getStyleProps(): array
	{
		// Optionally define which prop values will be used to assemble style attribute
		parent::getStyleProps();
		return [TestComponentProps::LINE_HEIGHT];
	}

	protected function beforeMapPropsToTemplate(ValidProps $props): void
	{
		parent::beforeMapPropsToTemplate($props);
		// do stuff before valid props are sent to template, e.g. assign extra params to template
		$this->template->setParameters(['undefinedProp' => $this->getMappedProp('undefinedProp')]);
	}

	protected function beforeRender(ValidProps $props): void
	{
		parent::beforeRender($props);
		// do stuff before component is rendered
	}

	/**
	 * @return class-string<TestComponentProps>
	 */
	protected function getPropsClass(): string
	{
		return TestComponentProps::class;
	}

}
