<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Application\IPresenterFactory;
use Nette\Application\PresenterFactory;
use Nette\DI\Container;
use Nette\InvalidStateException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Wavevision\PropsControl\PropsControl;
use Wavevision\PropsControlTests\Components\InvalidComponent;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponent;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponentProps;
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
		$this->control->render([TestComponentProps::STRING => 'some string']);
		$crawler = new Crawler(ob_get_clean());
		$root = $crawler->filter('div.test-component');
		$this->assertEquals(1, $root->count());
		$this->assertCount(2, $root->children());
		$parts = $crawler->filter('div.test-component-part__element');
		$this->assertCount(5, $parts);
		$this->assertTrue(strpos($parts->first()->attr('class'), 'first') !== false);
		$other = $crawler->filter('div.other-block');
		$this->assertEquals(1, $other->count());
		$this->assertCount(1, $other->children());
		$this->assertEquals('other-block other-block--some-modifier', $other->attr('class'));
	}

	public function testCreatePropsThrowsException(): void
	{
		$control = new InvalidComponent();
		$this->expectException(InvalidStateException::class);
		$control->render([]);
	}
}
