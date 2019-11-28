<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\MemberAccessException;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\ValidProps;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponentProps;

/**
 * @covers \Wavevision\PropsControl\ValidProps
 */
class ValidPropsTest extends TestCase
{

	public function testGet(): void
	{
		$validProps = new ValidProps(['prop' => 'value']);
		$this->assertEquals('value', $validProps->get('prop'));
	}

	public function testGetProps(): void
	{
		$props = new TestComponentProps();
		$validProps = new ValidProps([]);
		$validProps->setProps($props);
		$this->assertSame($props, $validProps->getProps());
	}

	public function testGetThrowsMemberAccessException(): void
	{
		$validProps = new ValidProps(['prop' => 'value']);
		$this->assertEquals('value', $validProps->prop);
		$this->expectException(MemberAccessException::class);
		$validProps->undefinedProp;
	}

	public function testSetThrowsMemberAccessException(): void
	{
		$validProps = new ValidProps([]);
		$this->expectException(MemberAccessException::class);
		$validProps->prop = 'value';
	}

}
