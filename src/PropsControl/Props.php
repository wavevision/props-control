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
		$this->schema = Expect::structure($this->define());
	}

	/**
	 * @param mixed[]|null $data
	 * @return object
	 */
	public function process(?array $data = null): object
	{
		if ($data === null) {
			$data = $this->data;
		}
		return $this->processor->process($this->schema, $data);
	}

	/**
	 * @return Schema[]
	 */
	abstract protected function define(): array;
}
