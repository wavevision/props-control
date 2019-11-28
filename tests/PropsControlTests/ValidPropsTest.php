<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\PropsControl\Exceptions\UndefinedProp;
use Wavevision\PropsControl\ValidProps;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponentProps;

/**
 * @covers \Wavevision\PropsControl\ValidProps
 */
class ValidPropsTest extends TestCase
{

	public function testGet(): void
	{
		$validProps = new ValidProps();
		$validProps->prop = 'value';
		$this->assertEquals('value', $validProps->get('prop'));
	}

	public function testGetProps(): void
	{
		$props = new TestComponentProps();
		$validProps = new ValidProps();
		$validProps->setProps($props);
		$this->assertSame($props, $validProps->getProps());
	}

	public function testGetThrowsUndefinedProp(): void
	{
		$validProps = new ValidProps();
		$validProps->prop = 'value';
		$this->assertEquals('value', $validProps->prop);
		$this->expectException(UndefinedProp::class);
		$validProps->undefinedProp;
	}

	public function testSetThrowsInvalidState(): void
	{
		$validProps = (new ValidProps())->lock();
		$this->expectException(InvalidState::class);
		$validProps->prop = 'value';
	}

}
