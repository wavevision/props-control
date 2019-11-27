<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use stdClass;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\PropsControl\Exceptions\UndefinedProp;

/**
 * @internal
 */
final class ValidProps extends stdClass
{

	/**
	 * @var bool
	 */
	private $locked = false;

	/**
	 * @var Props|null
	 */
	private $props;

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		if (!$this->isSet($name)) {
			throw new UndefinedProp("Prop '$name' does on exist in validated props object.");
		}
		return $this->$name;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set(string $name, $value): void
	{
		if ($this->locked) {
			throw new InvalidState("Cannot write prop '$name', validated props are read-only.");
		}
		$this->$name = $value;
	}

	/**
	 * @return mixed
	 */
	public function get(string $prop)
	{
		return $this->$prop;
	}

	/**
	 * @return mixed|null
	 */
	public function getNullable(string $prop)
	{
		return $this->isSet($prop) ? $this->$prop : null;
	}

	public function isSet(string $prop): bool
	{
		return isset($this->$prop);
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
