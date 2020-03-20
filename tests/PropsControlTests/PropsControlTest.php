<?php declare (strict_types = 1);

namespace Wavevision\PropsControlTests;

use Nette\Application\IPresenterFactory;
use Nette\Application\PresenterFactory;
use Nette\DI\Container;
use Nette\Schema\ValidationException;
use Nette\Utils\Html;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Wavevision\PropsControl\Exceptions\InvalidProps;
use Wavevision\PropsControl\Exceptions\InvalidState;
use Wavevision\PropsControl\PropsControl;
use Wavevision\PropsControl\ValidProps;
use Wavevision\PropsControlTests\Components\InvalidComponent;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponent;
use Wavevision\PropsControlTests\Components\TestComponent\TestComponentProps;
use Wavevision\PropsControlTests\Presenters\TestPresenter;

class PropsControlTest extends TestCase
{

	private Container $container;

	private PropsControl $control;

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

	public function testCallbacks(): void
	{
		$callback = function (ValidProps $props, $component): void {
			$this->assertEquals('test', $props->get(TestComponentProps::STRING));
			$this->assertInstanceOf(TestComponent::class, $component);
		};
		$this->control->addBeforeRender($callback);
		$this->control->addBeforeMapPropsToTemplate($callback);
		ob_start();
		$this->control->render([TestComponentProps::STRING => 'test']);
		ob_get_clean();
	}

	public function testRender(): void
	{
		ob_start();
		$entity = new \stdClass();
		$entity->enabled = true;
		$this->control->render(
			[
				TestComponentProps::ENTITY => $entity,
				TestComponentProps::STRING => 'some string',
				TestComponentProps::COLLECTION => [['one' => 'One', 'two' => 2]],
			]
		);
		$crawler = new Crawler(ob_get_clean());
		$root = $crawler->filter('div.tc');
		$this->assertEquals(
			'tc tc--boolean tc--one tc--custom tc--some-other-modifier',
			$root->attr('class')
		);
		$this->assertEquals('line-height:1.4', $root->attr('style'));
		$this->assertEquals(1, $root->count());
		$this->assertCount(3, $root->children());
		$parts = $crawler->filter('div.tc-part__element');
		$this->assertCount(5, $parts);
		$this->assertTrue(strpos($parts->first()->attr('class'), 'first') !== false);
		$this->assertTrue(strpos($parts->last()->attr('class'), 'last') !== false);
		$other = $crawler->filter('div.other-block');
		$this->assertEquals(1, $other->count());
		$this->assertCount(1, $other->children());
		$this->assertEquals('other-block other-block--some-modifier', $other->attr('class'));
		$shapes = $root->filter('div.tc__collection');
		$this->assertCount(1, $shapes->children());
		$this->assertEquals('One / 2', $shapes->children()->first()->text());
	}

	public function testRenderToHtml(): void
	{
		$this->assertInstanceOf(Html::class, $this->control->renderToHtml([TestComponentProps::STRING => 'string']));
	}

	public function testRenderToString(): void
	{
		$props = new \stdClass();
		$props->{TestComponentProps::STRING} = 'some string';
		$props->{TestComponentProps::COLLECTION} = [['one' => 'One', 'two' => 2]];
		$this->assertIsString($this->control->renderToString($props));
		$props = new TestComponentProps(
			[TestComponentProps::STRING => null, TestComponentProps::COLLECTION => []]
		);
		$this->expectException(ValidationException::class);
		$this->control->renderToString($props);
	}

	public function testRenderInvalidProps(): void
	{
		$this->expectException(InvalidProps::class);
		$this->control->render(1);
	}

	public function testCreatePropsThrowsException(): void
	{
		$control = new InvalidComponent();
		$this->expectException(InvalidState::class);
		$control->render([]);
	}

}
