<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Schema\Elements\Structure;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\SmartObject;

abstract class Props
{

	use SmartObject;

	/**
	 * @var mixed[]
	 */
	protected $data;

	/**
	 * @var Structure
	 */
	protected $schema;

	/**
	 * @var Processor
	 */
	private $processor;

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;
		$this->processor = new Processor();
		$this->schema = Expect::structure($this->define())->castTo(ProcessedProps::class);
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @param mixed[]|null $data
	 */
	final public function process(?array $data = null): ValidProps
	{
		/** @var ProcessedProps $props */
		$props = $this->processor->process($this->schema, $data ?: $this->getData());
		return (new ValidProps($props->getValues()))->setProps($this);
	}

	/**
	 * @return Schema[]
	 */
	abstract protected function define(): array;

}
