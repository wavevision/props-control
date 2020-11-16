# Understanding Props

For those who are familiar with [React.js](https://github.com/facebook/react) the core concept of Props is quite clear.

> Props stand for _properties_ which are arbitrary inputs to a component.

`PropsControl` brings this concept into Nette application with all its benefits.

## Readability and maintainability

Having a props definition next to your component means you can simply overview which data the component consumes and easily
make changes to such definition without polluting the component itself with otherwise useless class members and properties
which are only used to render some output.

## Immutability

Once you pass data into your component it cannot be changed. This means you cannot possibly break things at the moment when
nothing but rendering the output should happen.

## Validation

All your data inputs are safely validated with [nette/schema](https://github.com/nette/schema). Thanks to this powerful part of Nette
you can define complex data schemes through your components and make them work together (nested components).

## Encapsulation

We all know it – we have a component which renders some data and every time there's a little update needed,
the component's render method and / or properties just grow and grow. 

This will **not** happen with `PropsControl` as all the data passed will be encapsulated in an object
or array the component's render method will accept as a **single** parameter.
Once your data goes into your component and is validated, it gets encapsulated again,
this time in `Wavevision\PropsControl\ValidProps` object that is available in the template to read and render the data there.
