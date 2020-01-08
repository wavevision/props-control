<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Helpers;

use Nette\Schema\Expect;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Helpers\PropUtils;

/**
 * @covers \Wavevision\PropsControl\Helpers\PropUtils
 */
class PropUtilsTest extends TestCase
{

	public function testIsControllable(): void
	{
		$this->assertFalse(PropUtils::isControllable(''));
	}

	public function testMerge(): void
	{
		$this->assertEquals(
			['one' => Expect::int(), 'two' => Expect::bool()],
			PropUtils::merge(['one' => Expect::int()], ['two' => Expect::bool()])
		);
	}

}
