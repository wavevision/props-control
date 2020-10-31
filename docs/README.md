<p align="center"><a href="https://github.com/wavevision"><img alt="Wavevision s.r.o." src="https://wavevision.com/images/wavevision-logo.png" width="120" /></a></p>
<h1 align="center" id="props-control">PropsControl</h1>

[![Build Status](https://travis-ci.org/wavevision/props-control.svg?branch=master)](https://travis-ci.org/wavevision/props-control)
[![Coverage Status](https://coveralls.io/repos/github/wavevision/props-control/badge.svg?branch=master)](https://coveralls.io/github/wavevision/props-control?branch=master)
[![PHPStan](https://img.shields.io/badge/style-level%20max-brightgreen.svg?label=phpstan)](https://github.com/phpstan/phpstan)

## What is it

`PropsControl` is an abstract `Nette\Application\UI\Control` that can help you to create simple, yet powerful UI components with great maintainability.

## Features
- simple to use
- automatic template file assignment
- immutable props defined and validated using [`nette/schema`](https://github.com/nette/schema)
- CSS classname helper to seamlessly create whole classes based on current props
- style attribute helper to easily assign inline style to your component
- `beforeRender` method you know from presenters with ability to add callbacks from higher order components
- rendering to `string` and `Nette\Utils\Html`
