<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Helpers;

use Nette\Schema\Elements\AnyOf;
use Nette\Schema\Schema;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Helpers\PropTypes;

class PropTypesTest extends TestCase
{

	public function testBooleans(): void
	{
		foreach (PropTypes::booleans('one', 'two') as $boolean) {
			$this->assertInstanceOf(Schema::class, $boolean);
		}
	}

	public function testRenderable(): void
	{
		$this->assertInstanceOf(AnyOf::class, PropTypes::renderable());
	}

}
