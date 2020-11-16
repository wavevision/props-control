# Working with Props

As demonstrated in [First Component](getting-started/first-component.md) example, each `PropsControl` component needs its
Props for data consumption and output rendering.

## Definition

The definition will give us the shape of our props, so we can structure and validate them. 
Also, the constants used for the definition can be later used to create and get the data while avoiding typos in prop names.
Constants are also extremely useful when re-defining your props. If you refactor any constant in your IDE, the change will
get propagated through your whole application.

After we created our definition and linked it to our component we can use the definition object to wrap our data.

```php
$props = new FirstComponentProps([FirstComponentProps::STRING => 'world', FirstComponentProps::NUMBER => 22]);
```

This object can be then passed to our component's render method.

It can also be used in some other component's props definition.

```php
/**
 * @inheritDoc
 */
protected function define(): array
{
	return [
	    'firstComponent' => Expect::type(FirstComponentProps::class)->required(),
	];
}
```

This way you can make nested components and keep the validation working without duplicating the schemes.

## Reading props

You can access `Wavevision\PropsControl\ValidProps` object containing all validated props in your component's template
in `$props` variable.

Your template will be also provided with a `$definition` variable, which holds the definition class of your props.

Reading your props is pretty straightforward.

### Property access

```latte
{$props->propName}
{$props->{$definition::PROP_NAME}}
```

### Getter

```latte
{$props->get('propName')}
{$props->get($definition::PROP_NAME)}
```

### Errors

Props are read-only, `Wavevision\PropsControl\Exceptions\NotAllowed` exception will be thrown if:

- you're trying to access an undefined prop
- you're trying to write to a prop
- you're calling any method not defined in `Wavevision\PropsControl\ValidProps` class
