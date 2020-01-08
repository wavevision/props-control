<?php declare (strict_types = 1);

namespace Wavevision\PropsControl\Helpers;

use Nette\StaticClass;
use Nette\Utils\Html;

class Render
{

	use StaticClass;

	public static function toHtml(string $content): Html
	{
		return Html::el()->setHtml($content);
	}

}
