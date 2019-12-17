<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Exceptions\NotAllowed;
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

	public function testGetThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(['prop' => 'value']);
		$this->assertEquals('value', $validProps->prop);
		$this->expectException(NotAllowed::class);
		$validProps->undefinedProp;
	}

	public function testSetThrowsNotAllowed(): void
	{
		$validProps = new ValidProps([]);
		$this->expectException(NotAllowed::class);
		$validProps->prop = 'value';
	}

	public function testSetExistingPropsThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(['prop' => 'value']);
		$this->expectException(NotAllowed::class);
		$validProps->prop = 'anotherValue';
	}

	public function testCallThrowsNotAllowed(): void
	{
		$validProps = new ValidProps([]);
		$this->expectException(NotAllowed::class);
		$validProps->{'undefinedMethod'}();
	}

	public function testCallStaticThrowsNotAllowed(): void
	{
		$undefinedStaticMethod = 'undefinedStaticMethod';
		$this->expectException(NotAllowed::class);
		ValidProps::$undefinedStaticMethod();
	}

	public function testIsset(): void
	{
		$validProps = new ValidProps([]);
		$this->assertFalse(isset($validProps->someProp));
	}

	public function testUnset(): void
	{
		$validProps = new ValidProps(['prop' => 'value']);
		$this->expectException(NotAllowed::class);
		unset($validProps->prop);
	}

}
