<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;
use Wavevision\PropsControl\Exceptions\InvalidProps;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\Utils\Strings;

/**
 * @property-read PropsControlTemplate $template
 */
abstract class PropsControl extends BaseControl
{

	protected const USE_VALUE = 'USE_VALUE';

	private const CLASS_NAME = 'className';

	private const MODIFIERS = 'modifiers';

	private const PROPS = 'props';

	public function __construct()
	{
		$this->onCreateTemplate(
			function (Template $template): void {
				$template->setParameters([self::CLASS_NAME => $this->createClassName()]);
			}
		);
	}

	public function getClassName(): string
	{
		return Strings::camelCaseToDashCase($this->getControlName());
	}

	/**
	 * @return mixed[]
	 */
	public function getClassNameModifiers(): array
	{
		return [];
	}

	public function getControlName(): string
	{
		return Strings::getClassName(static::class, true);
	}

	/**
	 * @param mixed[]|object $props
	 */
	public function render($props): void
	{
		$this->prepareRender($props);
		$this->template->render();
	}

	/**
	 * @param mixed[]|object $props
	 * @return string
	 */
	public function renderToString($props): string
	{
		$this->prepareRender($props);
		return $this->template->renderToString();
	}

	protected function beforeMapPropsToTemplate(ValidProps $props): void
	{
	}

	protected function beforeRender(ValidProps $props): void
	{
	}

	/**
	 * @return string[]
	 */
	final protected function getMappedModifiers(): array
	{
		return $this->template->{self::MODIFIERS} ?? [];
	}

	/**
	 * @param string $prop
	 * @return mixed
	 */
	final protected function getMappedProp(string $prop)
	{
		if ($props = $this->getMappedProps()) {
			return $props->getNullable($prop);
		}
		return null;
	}

	final protected function getMappedProps(): ?ValidProps
	{
		return $this->template->{self::PROPS} ?? null;
	}

	final protected function mapPropsToTemplate(object $props): void
	{
		$props = $this->validateProps($props);
		$this->beforeMapPropsToTemplate($props);
		$this->template->{self::PROPS} = $props;
		$this->template->{self::MODIFIERS} = [];
		foreach ($this->getClassNameModifiers() as $k => $v) {
			if (is_callable($v)) {
				if ($modifier = $v($props)) {
					$this->template->{self::MODIFIERS}[] = is_string($modifier) ? $modifier : $k;
				}
				continue;
			}
			$value = $v === self::USE_VALUE;
			$modifier = $value ? $k : $v;
			if ($prop = $this->getMappedProp($modifier)) {
				$this->template->{self::MODIFIERS}[] = $value ? $prop : $modifier;
			}
		}
		$this->beforeRender($props);
	}

	abstract protected function getPropsClass(): string;

	/**
	 * @param mixed[]|object $props
	 */
	final protected function prepareRender($props): void
	{
		if (is_array($props)) {
			$props = $this->createProps($props);
		}
		if (!is_object($props)) {
			throw $this->createInvalidProps('Render props must be array|object', $props);
		}
		$this->mapPropsToTemplate($props);
	}

	private function createClassName(): ClassName
	{
		return new ClassName(
			$this->getClassName(),
			function (): array {
				return $this->getMappedModifiers();
			}
		);
	}

	/**
	 * @param mixed[] $props
	 * @return Props
	 */
	private function createProps(array $props): Props
	{
		$class = $this->getPropsClass();
		if (!class_exists($class)) {
			throw new InvalidState("Props definition '$class' does not exist.");
		}
		return new $class($props);
	}

	/**
	 * @param mixed[]|object $props
	 */
	private function createInvalidProps(string $message, $props): InvalidProps
	{
		return new InvalidProps(
			sprintf(
				'%s, "%s" given to "%s".',
				$message,
				gettype($props),
				static::class
			)
		);
	}

	private function validateProps(object $props): ValidProps
	{
		if ($props instanceof Props) {
			$props = $props->process();
		}
		if (!($props instanceof ValidProps)) {
			throw $this->createInvalidProps(
				sprintf('Mapped props must be an instance of "%s"', ValidProps::class),
				$props
			);
		}
		return $props;
	}

}
