<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\PropsUtils;

class PropsUtilsTest extends TestCase
{

	public function testBooleans(): void
	{
		foreach (PropsUtils::booleans('one', 'two') as $boolean) {
			$this->assertInstanceOf(Schema::class, $boolean);
		}
	}

	public function testMerge(): void
	{
		$this->assertEquals(
			['one' => Expect::int(), 'two' => Expect::bool()],
			PropsUtils::merge(['one' => Expect::int()], ['two' => Expect::bool()])
		);
	}

}
