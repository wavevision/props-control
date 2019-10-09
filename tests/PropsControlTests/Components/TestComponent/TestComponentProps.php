<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Nette\Schema\Expect;
use Wavevision\PropsControl\Props;

class TestComponentProps extends Props
{

	public const BOOLEAN_VALUE = 'boolean';

	public const COLLECTION = 'collection';

	public const NULLABLE_NUMBER = 'nullableNumber';

	public const STRING = 'string';

	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return [
			self::BOOLEAN_VALUE => Expect::bool(true),
			self::COLLECTION => Expect::arrayOf(
				Expect::structure(['one' => Expect::string(), 'two' => Expect::int(),])
			),
			self::NULLABLE_NUMBER => Expect::int()->nullable(),
			self::STRING => Expect::string()->required(),
		];
	}
}
