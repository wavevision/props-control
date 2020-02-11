<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Html;
use Wavevision\PropsControl\Exceptions\InvalidProps;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\PropsControl\Helpers\ClassName;
use Wavevision\PropsControl\Helpers\Render;
use Wavevision\PropsControl\Helpers\Style;
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

	private const STYLE = 'style';

	public function __construct()
	{
		$this->onCreateTemplate(
			function (Template $template): void {
				$template->setParameters(
					[
						self::CLASS_NAME => $this->createClassName(),
						self::STYLE => new Style(),
					]
				);
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
	 * @return string[]
	 */
	public function getStyleProps(): array
	{
		return [];
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
	 */
	public function renderToHtml($props): Html
	{
		return Render::toHtml($this->renderToString($props));
	}

	/**
	 * @param mixed[]|object $props
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

	final protected function mapPropsToTemplate(Props $props): void
	{
		$props = $props->process();
		$this->prepareStyle($props);
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
	 * @param mixed $props
	 */
	final protected function prepareRender($props): void
	{
		if (!is_array($props) && !is_object($props)) {
			throw $this->createInvalidProps('Render props must be array|object', $props);
		}
		$this->mapPropsToTemplate($props instanceof Props ? $props : $this->createProps((array)$props));
	}

	private function createClassName(): ClassName
	{
		return new ClassName(
			$this->getClassName(),
			fn(): array => $this->getMappedModifiers()
		);
	}

	/**
	 * @param mixed[] $props
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

	private function prepareStyle(ValidProps $props): void
	{
		foreach ($this->getStyleProps() as $prop) {
			if ($value = $props->get($prop)) {
				$this->template->style->add($prop, $value);
			}
		}
	}

}
