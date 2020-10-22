<?php declare (strict_types = 1);

namespace Wavevision\PropsControl\Helpers;

use Nette\SmartObject;
use Wavevision\PropsControl\Exceptions\InvalidArgument;
use Wavevision\Utils\Strings;
use function gettype;
use function implode;
use function is_scalar;
use function sprintf;

class Style
{

	use SmartObject;

	/**
	 * @var mixed[]
	 */
	private array $style = [];

	/**
	 * @param mixed $value
	 */
	public function add(string $key, $value): self
	{
		if (!is_scalar($value)) {
			throw new InvalidArgument(sprintf("Style attribute values must be scalar, '%s' given.", gettype($value)));
		}
		$this->style[$key] = $value;
		return $this;
	}

	public function __toString(): string
	{
		$style = [];
		foreach ($this->style as $key => $value) {
			$style[] = Strings::camelCaseToDashCase($key) . ':' . $value;
		}
		return implode(';', $style);
	}

}
