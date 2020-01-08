<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Helpers;

use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Helpers\ClassName;

/**
 * @covers \Wavevision\PropsControl\Helpers\ClassName
 */
class ClassNameTest extends TestCase
{

	public function testBlock(): void
	{
		$className = $this->createClassName('some-class', 'firstModifier');
		$this->assertEquals(
			'some-class some-class--first-modifier some-class--extra-modifier',
			$className->block('extraModifier')
		);
	}

	public function testCreate(): void
	{
		$className = $this->createClassName();
		$sub1 = $className->create('');
		$sub2 = $className->create('', false);
		$this->assertEquals('', $sub2->getBaseClass());
		$this->assertInstanceOf(ClassName::class, $sub1);
		$this->assertInstanceOf(ClassName::class, $sub2);
		$this->assertInstanceOf(ClassName::class, $sub1->setElementDelimiter(''));
		$this->assertInstanceOf(ClassName::class, $sub1->setModifierDelimiter(''));
		$this->assertInstanceOf(ClassName::class, $sub1->setSubBlockDelimiter(''));
	}

	public function testElement(): void
	{
		$className = $this->createClassName('my-class');
		$this->assertEquals('my-class__element', $className->element('element'));
	}

	public function testExtra(): void
	{
		$className = new ClassName('');
		$this->assertEquals('extra', $className->extra('extra'));
		$this->assertEquals('prefixed-extra', $className->extra('extra', 'prefixed'));
	}

	private function createClassName(string $baseClass = '', string ...$modifiers): ClassName
	{
		return new ClassName(
			$baseClass,
			function () use ($modifiers): array {
				return $modifiers;
			}
		);
	}

}
