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
		return static::CLASS_NAME ?: $this->getNameFromClass();
	}

	public function getNameFromClass(): string
	{
		return Strings::getClassName(static::class, true);
	}

	public function render(Props $props): void
	{
		$this->mapPropsToTemplate($props);
		$this->template->render();
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
	 * @return array<string>
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
		$this->template->{self::PROPS} = $props->process();
		$this->template->{self::MODIFIERS} = [];
		foreach (static::CLASS_NAME_MODIFIERS as $modifier) {
			if ($this->getMappedProp($modifier)) {
				$this->getMappedModifiers()[] = $modifier;
			}
		}
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

	private function getTemplateFile(): string
	{
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException('Unable to get filename for ' . static::class . '.');
		}
		return dirname($file) . '/templates/' . $this->getNameFromClass() . '.latte';
	}
}
