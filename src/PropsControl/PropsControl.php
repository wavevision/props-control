<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\InvalidStateException;
use Wavevision\Utils\Strings;

/**
 * @property-read PropsControlTemplate $template
 */
abstract class PropsControl extends Control
{

	public const CLASS_NAME = '';

	public const CLASS_NAME_MODIFIERS = [];

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
	 * @param mixed[] $props
	 */
	public function render(array $props): void
	{
		$this->renderObject($this->createProps($props));
	}

	public function renderObject(Props $props): void
	{
		$this->mapPropsToTemplate($props);
		$this->template->render();
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
	protected function getMappedModifiers(): array
	{
		return $this->template->{self::MODIFIERS} ?? [];
	}

	/**
	 * @param string $prop
	 * @return mixed
	 */
	protected function getMappedProp(string $prop)
	{
		if ($props = $this->getMappedProps()) {
			return $props->$prop ?? null;
		}
		return null;
	}

	protected function getMappedProps(): ?object
	{
		return $this->template->{self::PROPS} ?? null;
	}

	protected function mapPropsToTemplate(Props $props): void
	{
		$props = $props->process();
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
		/** @var string $file */
		$file = $this->getReflection()->getFileName();
		return dirname($file) . '/templates/' . $this->getNameFromClass() . '.latte';
	}
}
