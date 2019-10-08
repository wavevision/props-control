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
	public function __construct(array $data)
	{
		$this->data = $data;
		$this->processor = new Processor();
		$this->schema = Expect::structure($this->define());
	}

	public function getProcessor(): Processor
	{
		return $this->processor;
	}

	public function process(): object
	{
		return $this->processor->process($this->schema, $this->data);
	}

	/**
	 * @return Schema[]
	 */
	abstract protected function define(): array;
}
