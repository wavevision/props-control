<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;
use Nette\InvalidStateException;
use Wavevision\Utils\Arrays;
use Wavevision\Utils\Strings;

/**
 * @property-read PropsControlTemplate $template
 */
abstract class PropsControl extends Control
{

	public const CLASS_NAME = '';

	public const CLASS_NAME_MODIFIERS = [];

	protected const ELEMENT_DELIMITER = '__';

	protected const MODIFIER_DELIMITER = '--';

	private const MODIFIERS = 'modifiers';

	private const PROPS = 'props';

	public function getNameFromClass(): string
	{
		return Strings::getClassName(static::class, true);
	}

	public function getTemplateFile(): string
	{
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException('Unable to get filename for ' . static::class . '.');
		}
		return dirname($file) . '/templates/' . $this->getNameFromClass() . '.latte';
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
		$template->blockClass = [$this, 'getBlockClass'];
		$template->elementClass = [$this, 'getElementClass'];
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

	/**
	 * @param string $className
	 * @param array<string|null> $modifiers
	 * @return string
	 */
	private function composeClassNames(string $className, array $modifiers): string
	{
		$classNames = [$className];
		foreach ($this->filterModifiers($modifiers) as $modifier) {
			$classNames[] = $className . static::MODIFIER_DELIMITER . Strings::camelCaseToDashCase($modifier);
		}
		return implode(' ', $classNames);
	}

	/**
	 * @param array<string|null> $modifiers
	 * @return array<string>
	 */
	private function filterModifiers(array $modifiers): array
	{
		return array_filter(
			$modifiers,
			function (?string $modifier): bool {
				return $modifier !== null;
			}
		);
	}

	private function getBaseClass(): string
	{
		return static::CLASS_NAME ?: $this->getNameFromClass();
	}

	private function getBlockClass(?string ...$modifiers): string
	{
		return $this->composeClassNames(
			$this->getBaseClass(),
			Arrays::mergeAllRecursive($this->getMappedModifiers(), $modifiers)
		);
	}

	private function getElementClass(string $className, ?string ...$modifiers): string
	{
		return $this->composeClassNames($this->getBaseClass() . static::ELEMENT_DELIMITER . $className, $modifiers);
	}
}
