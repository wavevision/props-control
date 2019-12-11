<?php declare (strict_types = 1);

namespace Wavevision\PropsControl;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\StaticClass;
use Wavevision\Utils\Arrays;

final class PropsUtils
{

	use StaticClass;

	/**
	 * @return array<string, Schema>
	 */
	public static function booleans(string ...$props): array
	{
		/** @var array<string, Schema> $booleans */
		$booleans = Arrays::mapKeysFromValues(
			$props,
			function (string $prop): array {
				return [$prop, Expect::bool(false)];
			}
		);
		return $booleans;
	}

	/**
	 * @param Schema[] ...$props
	 * @return Schema[]
	 */
	public static function merge(array ...$props): array
	{
		return Arrays::mergeAllRecursive(...$props);
	}

}
