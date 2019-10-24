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

	public const TYPE = 'type';

	public const TYPE_ONE = 'one';

	public const TYPE_TWO = 'two';

	public const TYPES = [self::TYPE_ONE, self::TYPE_TWO];

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
			self::TYPE => Expect::anyOf(...self::TYPES)->default(self::TYPE_ONE),
		];
	}
}
