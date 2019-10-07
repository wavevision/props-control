<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Nette\Schema\Elements\Structure;
use Nette\Schema\Expect;
use Wavevision\PropsControl\Props;

class TestProps extends Props
{

	public const BOOLEAN = 'boolean';

	public const NULLABLE_NUMBER = 'nullableNumber';

	public const STRING = 'string';

	protected function define(): Structure
	{
		return Expect::structure(
			[
				self::BOOLEAN => Expect::bool(false),
				self::NULLABLE_NUMBER => Expect::int()->nullable(),
				self::STRING => Expect::string()->required(),
			]
		);
	}
}
