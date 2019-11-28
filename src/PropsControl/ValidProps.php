<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\SmartObject;
use stdClass;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\PropsControl\Exceptions\UndefinedProp;

/**
 * @internal
 */
final class ValidProps extends stdClass
{

	use SmartObject;

	/**
	 * @var bool
	 */
	private $locked = false;

	/**
	 * @var Props|null
	 */
	private $props;

	/**
	 * @var mixed[]
	 */
	private $values = [];

	/**
	 * @return mixed
	 */
	public function &__get(string $name)
	{
		if (!$this->isSet($name)) {
			throw new UndefinedProp("Prop '$name' does on exist in validated props object.");
		}
		return $this->values[$name];
	}

	/**
	 * @param mixed $value
	 */
	public function __set(string $name, $value): void
	{
		if ($this->locked) {
			throw new InvalidState("Cannot write prop '$name', validated props are read-only.");
		}
		$this->values[$name] = $value;
	}

	/**
	 * @return mixed
	 */
	public function get(string $prop)
	{
		return $this->values[$prop];
	}

	/**
	 * @return mixed|null
	 */
	public function getNullable(string $prop)
	{
		return $this->isSet($prop) ? $this->get($prop) : null;
	}

	public function isSet(string $prop): bool
	{
		return array_key_exists($prop, $this->values);
	}

	public function lock(): self
	{
		$this->locked = true;
		return $this;
	}

	public function getProps(): ?Props
	{
		return $this->props;
	}

	public function setProps(Props $props): self
	{
		$this->props = $props;
		return $this;
	}

}
