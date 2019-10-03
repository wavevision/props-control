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

	protected const MODIFIERS = 'modifiers';

	protected const PROPS = 'props';

	private const ELEMENT_DELIMITER = '__';

	private const MODIFIER_DELIMITER = '--';

	public function createTemplate(): ITemplate
	{
		/** @var PropsControlTemplate $template */
		$template = parent::createTemplate();
		$template->{self::MODIFIERS} = [];
		$template->blockClass = [$this, 'getBlockClass'];
		$template->elementClass = [$this, 'getElementClass'];
		$template->setFile($this->getTemplateFile());
		return $template;
	}

	public function getNameFromClass(): string
	{
		return Strings::getClassName(static::class, true);
	}

	public function render(Props $props): void
	{
		$this->mapPropsToTemplate($props->process());
		$this->template->render();
	}

	/**
	 * @param string $prop
	 * @return mixed
	 */
	protected function getTemplateProp(string $prop)
	{
		return $this->template->{self::PROPS}->$prop ?? null;
	}

	/**
	 * @param string $className
	 * @param array<string> $modifiers
	 * @return string
	 */
	private function composeClassNames(string $className, array $modifiers): string
	{
		$classNames = [$className];
		foreach ($modifiers as $modifier) {
			$classNames[] = $className . self::MODIFIER_DELIMITER . Strings::camelCaseToDashCase($modifier);
		}
		return implode(' ', $classNames);
	}

	private function getBaseClass(): string
	{
		return static::CLASS_NAME ?: $this->getNameFromClass();
	}

	private function getBlockClass(): string
	{
		return $this->composeClassNames($this->getBaseClass(), $this->template->{self::MODIFIERS});
	}

	private function getElementClass(string $className, string ...$modifiers): string
	{
		return $this->composeClassNames($this->getBaseClass() . self::ELEMENT_DELIMITER . $className, $modifiers);
	}

	private function getTemplateFile(): string
	{
		$file = $this->getReflection()->getFileName();
		if ($file === false) {
			throw new InvalidStateException('Unable to get filename for ' . static::class . '.');
		}
		return dirname($file) . '/templates/' . $this->getNameFromClass() . '.latte';
	}

	private function mapPropsToTemplate(object $props): void
	{
		$this->template->{self::PROPS} = $props;
		foreach (static::CLASS_NAME_MODIFIERS as $modifier) {
			if ($this->getTemplateProp($modifier)) {
				$this->template->{self::MODIFIERS}[] = $modifier;
			}
		}
	}
}
