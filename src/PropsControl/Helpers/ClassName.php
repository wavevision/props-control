<?php declare (strict_types = 1);

namespace Wavevision\PropsControl\Helpers;

use Nette\SmartObject;
use Wavevision\Utils\Strings;
use function array_filter;
use function array_merge;
use function array_unique;
use function implode;
use function is_callable;

class ClassName
{

	use SmartObject;

	public const PROP = 'className';

	private const ELEMENT_DELIMITER = '__';

	private const MODIFIER_DELIMITER = '--';

	private const SUB_BLOCK_DELIMITER = '-';

	private string $baseClass;

	private string $elementDelimiter;

	private string $modifierDelimiter;

	/**
	 * @var callable(): array<string>|null
	 */
	private $modifiersCallback;

	private string $subBlockDelimiter;

	public function __construct(string $baseClass, ?callable $modifiersCallback = null)
	{
		$this->baseClass = $baseClass;
		$this->elementDelimiter = self::ELEMENT_DELIMITER;
		$this->modifierDelimiter = self::MODIFIER_DELIMITER;
		$this->modifiersCallback = $modifiersCallback;
		$this->subBlockDelimiter = self::SUB_BLOCK_DELIMITER;
	}

	public function block(?string ...$modifiers): string
	{
		if (is_callable($this->modifiersCallback)) {
			$modifiers = array_unique(array_merge(($this->modifiersCallback)(), $modifiers));
		}
		return $this->composeClassNames($this->baseClass, $modifiers);
	}

	public function create(string $baseClass, bool $subBlock = true, bool $excludeModifiers = false): self
	{
		if ($subBlock) {
			$baseClass = $this->baseClass . $this->subBlockDelimiter . $baseClass;
		}
		return new self($baseClass, $excludeModifiers ? null : $this->modifiersCallback);
	}

	public function element(string $className, ?string ...$modifiers): string
	{
		return $this->composeClassNames($this->baseClass . $this->elementDelimiter . $className, $modifiers);
	}

	public function extra(string $className, string $prefix = ''): string
	{
		if ($prefix !== '') {
			return $prefix . $this->subBlockDelimiter . $className;
		}
		return $className;
	}

	public function getBaseClass(): string
	{
		return $this->baseClass;
	}

	public function setElementDelimiter(string $elementDelimiter): self
	{
		$this->elementDelimiter = $elementDelimiter;
		return $this;
	}

	public function setModifierDelimiter(string $modifierDelimiter): self
	{
		$this->modifierDelimiter = $modifierDelimiter;
		return $this;
	}

	public function setSubBlockDelimiter(string $subBlockDelimiter): self
	{
		$this->subBlockDelimiter = $subBlockDelimiter;
		return $this;
	}

	/**
	 * @param array<string|null> $modifiers
	 */
	private function composeClassNames(string $className, array $modifiers): string
	{
		$classNames = [$className];
		foreach ($this->filterModifiers($modifiers) as $modifier) {
			$classNames[] = $className . $this->modifierDelimiter . Strings::camelCaseToDashCase($modifier);
		}
		return implode(' ', $classNames);
	}

	/**
	 * @param array<string|null> $modifiers
	 * @return string[]
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

}
