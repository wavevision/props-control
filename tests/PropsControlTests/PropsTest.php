<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControlTests\Components\TestComponent\TestProps;

/**
 * @covers \Wavevision\PropsControl\Props
 */
class PropsTest extends TestCase
{

	public function testProcess(): void
	{
		$props = new TestProps([]);
		$this->assertInstanceOf(Processor::class, $props->getProcessor());
		$this->expectException(ValidationException::class);
		$props->process();
	}
}
