<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Schema\Elements\Structure;
use Nette\Schema\Processor;
use Nette\SmartObject;

abstract class Props
{

	use SmartObject;

	/**
	 * @var array<mixed>
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
	 * @param array<mixed> $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
		$this->processor = new Processor();
		$this->schema = $this->define();
	}

	public function getProcessor(): Processor
	{
		return $this->processor;
	}

	public function process(): object
	{
		return $this->processor->process($this->schema, $this->data);
	}

	abstract protected function define(): Structure;
}
