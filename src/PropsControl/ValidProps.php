<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\MemberAccessException;
use Nette\SmartObject;
use stdClass;

final class ValidProps extends stdClass
{

	use SmartObject;

	/**
	 * @var Props|null
	 */
	private $props;

	/**
	 * @var mixed[]
	 */
	private $values;

	/**
	 * @param mixed[] $values
	 */
	public function __construct(array $values)
	{
		$this->values = $values;
	}

	/**
	 * @return mixed
	 */
	public function &__get(string $name)
	{
		if (!$this->isSet($name)) {
			throw new MemberAccessException("Prop '$name' does on exist in validated props object.");
		}
		return $this->values[$name];
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
