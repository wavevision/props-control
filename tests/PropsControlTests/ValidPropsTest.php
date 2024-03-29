<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Utils\ArrayHash;
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
		$validProps = new ValidProps(new TestComponentProps(), ['prop' => 'value']);
		$this->assertEquals('value', $validProps->get('prop'));
	}

	public function testGetNullable(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), ['prop' => 'value', 'another' => null]);
		$this->assertEquals('fallback', $validProps->getNullable('another', 'fallback'));
		$this->assertNull($validProps->getNullable('undefined'));
	}

	public function testGetProps(): void
	{
		$props = new TestComponentProps();
		$validProps = new ValidProps($props, []);
		$this->assertSame($props, $validProps->getProps());
	}

	public function testToArray(): void
	{
		$props = [];
		$validProps = new ValidProps(new TestComponentProps(), $props);
		$this->assertSame($validProps->toArray(), $props);
	}

	public function testToArrayHash(): void
	{
		$props = ['prop' => 'value'];
		$validProps = new ValidProps(new TestComponentProps(), $props);
		$this->assertEquals(ArrayHash::from($props), $validProps->toArrayHash());
	}

	public function testToJson(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), []);
		$this->assertIsString($validProps->toJson());
	}

	public function testGetThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), ['prop' => 'value']);
		$this->assertEquals('value', $validProps->prop);
		$this->expectException(NotAllowed::class);
		$validProps->undefinedProp; //@phpstan-ignore-line
	}

	public function testSetThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), []);
		$this->expectException(NotAllowed::class);
		$validProps->prop = 'value';
	}

	public function testSetExistingPropsThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), ['prop' => 'value']);
		$this->expectException(NotAllowed::class);
		$validProps->prop = 'anotherValue';
	}

	public function testCallThrowsNotAllowed(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), []);
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
		$validProps = new ValidProps(new TestComponentProps(), []);
		$this->assertFalse(isset($validProps->someProp));
	}

	public function testUnset(): void
	{
		$validProps = new ValidProps(new TestComponentProps(), ['prop' => 'value']);
		$this->expectException(NotAllowed::class);
		unset($validProps->prop);
	}

}
