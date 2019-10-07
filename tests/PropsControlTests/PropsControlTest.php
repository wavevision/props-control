<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Application\IPresenterFactory;
use Nette\Application\PresenterFactory;
use Nette\DI\Container;
use Nette\Schema\ValidationException;
use PHPUnit\Framework\TestCase;
use Wavevision\PropsControl\PropsControl;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponent;
use Wavevision\PropsControlTests\Components\TestComponent\TestProps;
use Wavevision\PropsControlTests\Presenters\TestPresenter;

/**
 * @covers \Wavevision\PropsControl\PropsControl
 */
class PropsControlTest extends TestCase
{

	/**
	 * @var Container
	 */
	private $container;

	/**
	 * @var PropsControl
	 */
	private $control;

	public function setUp(): void
	{
		$this->container = Environment::getContainer();
		$this->control = new TestComponent();
		/** @var PresenterFactory $presenterFactory */
		$presenterFactory = $this->container->getByType(IPresenterFactory::class);
		/** @var TestPresenter $presenter */
		$presenter = $presenterFactory->createPresenter(
			$presenterFactory->unformatPresenterClass(TestPresenter::class)
		);
		$presenter->addComponent($this->control, 'testControl');
	}

	public function testRender(): void
	{
		ob_start();
		$this->control->render(new TestProps([TestProps::STRING => 'some string']));
		$output = ob_get_clean();
		$this->assertEquals("some string\n", $output);
	}

	public function testRenderThrowsValidationException(): void
	{
		$this->expectException(ValidationException::class);
		$this->control->render(new TestProps([]));
	}
}
