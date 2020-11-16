# First component

Each component will consist of three related files that will work together:

- [Props](#props)
- [Control](#control)
- [Template](#template)

## Props

To create our first component, let us start with defining its props, so we know which data our control will use.
For ease of this example we name it `FirstComponentProps`.

```php
use Nette\Schema\Expect;
use Wavevision\PropsControl\Props;

class FirstComponentProps extends Props
{

	public const NUMBER = 'number';

	public const STRING = 'string';


	/**
	 * @inheritDoc
	 */
	protected function define(): array
	{
		return [
			self::NUMBER => Expect::int()->nullable(),
			self::STRING => Expect::string()->required(),
		];
	}

}
```

In our props definition we:

- extended `Wavevision\PropsControl\Props` abstract class
- defined our prop names with constants
- implemented mandatory `define` method which returns the shape of our props for `Nette\Schema`

## Control

Now we can create our component control class which will take care of everything we need to use our component.

```php
use Wavevision\PropsControl\PropsControl;

class FirstComponent extends PropsControl
{
	
	/**
	 * @return class-string<FirstComponentProps>
	 */
	protected function getPropsClass(): string
	{
		return FirstComponentProps::class;
	}

}
```

In our control class we:

- extended `Wavevision\PropsControl\PropsControl` which does all the _goodies_ for us
- implemented mandatory `getPropsClass` method which _tells_ our component to work
with the props schema defined in that specific class

## Template

Each component will automatically assign a template to render in `templates/default.latte` in your component's namespace.
Based on our props and control definition it can render the component like this:

```latte
{templateType Wavevision\PropsControl\PropsControlTemplate}
<div n:class="$className->block()">
	<p>Hello, {$props->string}!</p>
	<p n:if="$props->number">Your number is {$props->number}</p>
</div>
```

## Let's roll!

Yes, now you can use your component as you're used to. Just create an instance (directly or with factory interface)
in your presenter or other component and start using it right away!

When you're rendering it, simply pass the data to it in your template:

```latte
{control firstComponent, [string => 'world', number => 22]}
```

That will output:

```html
<div class="first-component">
	<p>Hello, world!</p>
	<p>Your number is 22</p>
</div>
```

In case we would pass to the component something like this:

```latte
{control firstComponent, [number => '22']}
```

Our component will tell us we're doing something wrong as we defined the `string` prop as required and the `number` prop as `integer`,
thus `Nette\Schema\ValidationException` will be thrown.

This is just a very basic example, keep reading the docs to find out more information about all features and concepts of `PropsControl`.
