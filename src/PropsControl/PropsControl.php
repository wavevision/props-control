<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Wavevision\Utils\Strings;

/**
 * @property-read PropsControlTemplate $template
 */
abstract class PropsControl extends Control
{

	public const CLASS_NAME = '';

	public const CLASS_NAME_MODIFIERS = [];

	protected const DEFAULT_TEMPLATE = 'default';

	private const MODIFIERS = 'modifiers';

	private const PROPS = 'props';

	public function getBaseClassName(): string
	{
		return static::CLASS_NAME ?: Strings::camelCaseToDashCase($this->getNameFromClass());
	}

	public function getNameFromClass(): string
	{
		return Strings::getClassName(static::class, true);
	}

	/**
	 * @param object|mixed[] $props
	 */
	public function render($props): void
	{
		$this->renderControl($props);
	}

	protected function beforeMapPropsToTemplate(object $props): void
	{
	}

	protected function beforeRender(object $props): void
	{
	}

	protected function createTemplate(): ITemplate
	{
		/** @var PropsControlTemplate $template */
		$template = parent::createTemplate();
		$template->className = $this->createClassName();
		$template->setFile($this->getTemplateFile());
		return $template;
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
			return $props->$prop ?? null;
		}
		return null;
	}

	final protected function getMappedProps(): ?object
	{
		return $this->template->{self::PROPS} ?? null;
	}

	final protected function mapPropsToTemplate(object $props): void
	{
		if ($props instanceof Props) {
			$props = $props->process();
		}
		$this->beforeMapPropsToTemplate($props);
		$this->template->{self::PROPS} = $props;
		$this->template->{self::MODIFIERS} = [];
		foreach (static::CLASS_NAME_MODIFIERS as $modifier) {
			if ($this->getMappedProp($modifier)) {
				$this->template->{self::MODIFIERS}[] = $modifier;
			}
		}
		$this->beforeRender($props);
	}

	/**
	 * @param mixed[]|object $props
	 */
	final protected function renderControl($props): void
	{
		if (is_array($props)) {
			$props = $this->createProps($props);
		}
		if (!is_object($props)) {
			throw new InvalidArgumentException(
				sprintf('Render props must be array|object, "%s" given to "%s".', gettype($props), static::class)
			);
		}
		$this->mapPropsToTemplate($props);
		$this->template->render();
	}

	private function createClassName(): ClassName
	{
		return new ClassName(
			$this->getBaseClassName(),
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
		$class = static::class . Strings::firstUpper(self::PROPS);
		if (!class_exists($class)) {
			throw new InvalidStateException("Props definition '$class' does not exist.");
		}
		return new $class($props);
	}

	private function getTemplateFile(): string
	{
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException(sprintf('Unable to get filename for "%s".', static::class));
		}
		return dirname($file) . '/templates/' . static::DEFAULT_TEMPLATE . '.latte';
	}
}
