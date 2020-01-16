<?php declare (strict_types = 1);

namespace Wavevision\PropsControl\Helpers;

use Nette\Application\UI\Control;
use Nette\Schema\Elements\AnyOf;
use Nette\Schema\Elements\Type;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\StaticClass;
use Nette\Utils\Html;
use Wavevision\Utils\Arrays;

class PropTypes
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

	public static function controllable(): Type
	{
		return Expect::type(Control::class);
	}

	public static function html(): Type
	{
		return Expect::type(Html::class);
	}

	public static function pureRenderable(): AnyOf
	{
		return Expect::anyOf(Expect::string(), self::html());
	}

	public static function renderable(): AnyOf
	{
		return Expect::anyOf(self::controllable(), self::pureRenderable());
	}

}
