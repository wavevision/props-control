<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\SmartObject;

/**
 * @internal
 */
final class ProcessedProps
{

	use SmartObject;

	/**
	 * @var mixed[]
	 */
	private array $values;

	public function __construct()
	{
		$this->values = [];
	}

	/**
	 * @param mixed $value
	 */
	public function __set(string $name, $value): void
	{
		$this->values[$name] = $value;
	}

	/**
	 * @return mixed[]
	 */
	public function getValues(): array
	{
		return $this->values;
	}

}
