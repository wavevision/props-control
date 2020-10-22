<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Helpers;

use PHPUnit\Framework\TestCase;
use stdClass;
use Wavevision\PropsControl\Exceptions\InvalidArgument;
use Wavevision\PropsControl\Helpers\Style;

class StyleTest extends TestCase
{

	public function testAdd(): void
	{
		$style = new Style();
		$this->assertSame($style, $style->add('key', 1));
	}

	public function testAddThrowsInvalidArgument(): void
	{
		$style = new Style();
		$this->expectException(InvalidArgument::class);
		$style->add('key', new stdClass());
	}

}
