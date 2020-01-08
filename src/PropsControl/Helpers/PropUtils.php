<?php declare (strict_types = 1);

namespace Wavevision\PropsControl\Helpers;

use Nette\Application\UI\Control;
use Nette\Schema\Schema;
use Nette\StaticClass;
use Wavevision\Utils\Arrays;

class PropUtils
{

	use StaticClass;

	/**
	 * @param mixed $prop
	 */
	public static function isControllable($prop): bool
	{
		return $prop instanceof Control;
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
