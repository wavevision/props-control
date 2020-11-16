# CSS class helper

`PropsControl` provides you with a simple helper to assemble your component's CSS class names.
The helper formats classes with [simplified BEM](https://github.com/inuitcss) syntax.

## Base class

Our component's base class name is defined in `getClassName` method. By default, it will create `dash-cased` class name
from your component's control class, e.g `MyComponent` will result in `my-component`.

If you wish to introduce a different way of creating your base class, 
or you simply want to define it manually, you can do it as follows:

```php
public function getClassName(): string
{
	return 'base-class';
}
```

## Modifiers

It's very common a component we create needs to modify its class name based on the data input. We can easily achieve
this with `PropsControl` by defining `getClassNameModifiers` method.

The method will return an array of our modifiers definitions.

### Prop name

The simplest way to use a prop as modifier is to include its name in the array:

```php
public function getClassNameModifiers(): array
{
    return [MyComponentProps::SOME_PROP];
}
```

If `MyComponentProps::SOME_PROP` has a truthy value the **prop name** is used as a modifier.

```html
<div class="base-class base-class--some-prop"></div>
```

### Prop value

If you need to use the prop's value as a modifier instead, mark it with a constant predefined in `PropsControl`:

```php
public function getClassNameModifiers(): array
{
    return [MyComponentProps::SOME_PROP => self::USE_VALUE];
}
```

If `MyComponentProps::SOME_PROP` has a truthy value the **value** is used as a modifier.

```html
<div class="base-class base-class--some-value"></div>
```

### Custom modifiers

You can also define custom modifiers using a callback function that will receive `Wavevision\PropsControl\ValidProps`
object as an argument.

```php
public function getClassNameModifiers(): array
{
	return [
		'custom' => function (ValidProps $props): bool {
			if ($entity = $props->get(MyComponentProps::ENTITY)) {
				return $entity->enabled;
			}
			return false;
		},
		fn (ValidProps $props): string => $props->get(MyComponentProps::IS_VALID) ? 'enabled' : 'disabled',
	];
}
```

It this example, `custom` will be used as a modifier if the callback returns `true`.

The other callback shows we can also return a `string`, in that case the returned string is a modifier.

```html
<div class="base-class base-class--custom base-class--disabled"></div>
```

## Formatting the CSS class

Once you have defined everything and your component is being rendered, its template will be provided with
`Wavevision\PropsControl\Helpers\ClassName` object in `$className` variable 
that serves as a formatter with predefined base class and its modifiers.

The formatter has following methods publicly available:

### `block(?string ...$modifiers): string`

Formats a block class name with optional inline modifiers passed as parameters.

### `element(string $className, ?string ...$modifiers): string`

Formats an element class name as `base-class__$className` with optional inline modifiers passed as parameters.

### `create(string $baseClass, bool $block = true, bool $excludeModifiers = false): ClassName`

Creates a new formatter instance with a new base class. Useful for nested blocks inside your components. 

By default, the format is `base-class-$baseClass`. If `$block` is `false`, only `$baseClass` will be used.

If `$excludeModifiers` is `true`, **no modifiers** defined in the component will be passed to the newly instantiated formatter.
