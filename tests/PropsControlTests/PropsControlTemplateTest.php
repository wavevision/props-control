<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Latte\Engine;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\Exceptions\NotAllowed;
use Wavevision\PropsControl\PropsControlTemplate;

class PropsControlTemplateTest extends TestCase
{

	public function testAdd(): void
	{
		$template = $this->template();
		$template->add('prop', 1);
		$this->expectException(NotAllowed::class);
		$template->add('prop', 0);
	}

	public function testSetParameters(): void
	{
		$template = $this->template();
		$this->assertSame($template, $template->setParameters([]));
	}

	private function template(): PropsControlTemplate
	{
		return new PropsControlTemplate(new Engine());
	}

}
