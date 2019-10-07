<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests\Components\TestComponent;

use Wavevision\PropsControl\PropsControl;

class TestComponent extends PropsControl
{

	public const CLASS_NAME = 'test-component';

	public const CLASS_NAME_MODIFIERS = [TestProps::BOOLEAN];
}
