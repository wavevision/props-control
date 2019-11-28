<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components;

use Wavevision\PropsControl\PropsControl;

class InvalidComponent extends PropsControl
{

	protected function getPropsClass(): string
	{
		return '';
	}

}
