# MvcCore - Extension - Form - Field - Numeric

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.2.4-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-form-field-numeric/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

MvcCore form extension with input field types number and range.

## Installation
```shell
composer require mvccore/ext-form-field-numeric
```

## Fields And Default Validators
- `input:number`
	- `Number`
		- **configured by default**
		- raw user input parsing by specific rules to parse int/float or by `Intl` PHP extension
		- min., max. and step validation
	- `Integer`
		- not configured by default
		- the same validation as `Number`, only with `int` checking (if there are precision values or not)
	- `Float`
		- not configured by default
		- the same validation as `Number`, only returning always `float` type
- `input:range` (extended from `input:number`)
	- `Range`
		- **configured by default**
		- directly extended from `Number` valudator, the same functionality only for 2 numbers

## Features
- always server side checked attributes `required`, `disabled` and `readonly`
- all HTML5 specific and global atributes (by [Mozilla Development Network Docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Reference))
- every field has it's build-in specific validator described above
- every build-in validator adds form error (when necessary) into session
  and than all errors are displayed/rendered and cleared from session on error page, 
  where user is redirected after submit
- any field is possible to render naturally or with custom template for specific field class/instance
- very extensible field classes - every field has public template methods:
	- `SetForm()`		- called immediatelly after field instance is added into form instance
	- `PreDispatch()`	- called immediatelly before any field instance rendering type
	- `Render()`		- called on every instance in form instance rendering process
		- submethods: `RenderNaturally()`, `RenderTemplate()`, `RenderControl()`, `RenderLabel()` ...
	- `Submit()`		- called on every instance when form is submitted

## Examples
- [**Example - CD Collection (mvccore/example-cdcol)**](https://github.com/mvccore/example-cdcol)
- [**Application - Questionnaires (mvccore/app-questionnaires)**](https://github.com/mvccore/app-questionnaires)

## Basic Example

```php
$form = (new \MvcCore\Ext\Form($controller))->SetId('demo');
...
$yourAge = new \MvcCore\Ext\Forms\Fields\Number();
$yourAge
	->SetName('your_age')
	->SetLabel('Your Age')
	->SetMin(0)
	->SetMax(130)
	->SetStep(1)
	->SetValidators('Integer');
$schoolAge = new \MvcCore\Ext\Forms\Fields\Range([
	'name'		=> 'school_age',
	'label'		=> 'Your school age from/to',
	'min'		=> 0,
	'max'		=> 130,
	'step'		=> 1,
	'validators'	=> ['Range'],
]);
...
$form->AddFields($yourAge, $schoolAge);
```
