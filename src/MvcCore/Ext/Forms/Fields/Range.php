<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Fields;

/**
 * Responsibility: init, predispatch and render `<input>` HTML element 
 *				   with type `range`. `Range` field has it's own 
 *				   validator(s) to parse and check submitted value by
 *				   min/max/step/pattern. This field always 
 *				   return parsed `float` or `NULL` or for `multiple`
 *				   field it always return array of `float`s or empty array.
 */
class Range 
	extends		\MvcCore\Ext\Forms\Fields\Number
	implements	\MvcCore\Ext\Forms\Fields\IMultiple
{
	use \MvcCore\Ext\Forms\Field\Props\Multiple;

	/**
	 * Possible value: `range`.
	 * @var string
	 */
	protected $type = 'range';

	/**
	 * Validators: 
	 * - `Range` -  validate numeric raw user input. Parse numeric value (or two values) by locale conventions
	 *				and check minimum, maximum and step if necessary.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Range'];

	/**
	 * Supporting javascript full javascript class name.
	 * If you want to use any custom supporting javascript prototyped class
	 * for any additional purposes for your custom field, you need to use
	 * `$field->jsSupportingFile` property to define path to your javascript file
	 * relatively from configured `\MvcCore\Ext\Form::SetJsSupportFilesRootDir(...);`
	 * value. Than you have to add supporting javascript file path into field form 
	 * in `$field->PreDispatch();` method to render those files immediatelly after form
	 * (once) or by any external custom assets renderer configured by:
	 * `$form->SetJsSupportFilesRenderer(...);` method.
	 * Or you can add your custom supporting javascript files into response by your 
	 * own and also you can run your helper javascripts also by your own. Is up to you.
	 * `NULL` by default.
	 * @var string
	 */
	protected $jsClassName = 'MvcCoreForm.Range';

	/**
	 * Field supporting javascript file relative path.
	 * If you want to use any custom supporting javascript file (with prototyped 
	 * class) for any additional purposes for your custom field, you need to 
	 * define path to your javascript file relatively from configured 
	 * `\MvcCore\Ext\Form::SetJsSupportFilesRootDir(...);` value. 
	 * Than you have to add supporting javascript file path into field form 
	 * in `$field->PreDispatch();` method to render those files immediatelly after form
	 * (once) or by any external custom assets renderer configured by:
	 * `$form->SetJsSupportFilesRenderer(...);` method.
	 * Or you can add your custom supporting javascript files into response by your 
	 * own and also you can run your helper javascripts also by your own. Is up to you.
	 * `NULL` by default.
	 * @var string
	 */
	protected $jsSupportingFile = \MvcCore\Ext\Forms\IForm::FORM_ASSETS_DIR_REPLACEMENT . '/fields/range.js';
	
	/**
	 * Field supporting css file relative path.
	 * If you want to use any custom supporting css file 
	 * for any additional purposes for your custom field, you need to 
	 * define path to your css file relatively from configured 
	 * `\MvcCore\Ext\Form::SetCssSupportFilesRootDir(...);` value. 
	 * Than you have to add supporting css file path into field form 
	 * in `$field->PreDispatch();` method to render those files immediatelly after form
	 * (once) or by any external custom assets renderer configured by:
	 * `$form->SetCssSupportFilesRenderer(...);` method.
	 * Or you can add your custom supporting css files into response by your 
	 * own and also you can run your helper css also by your own. Is up to you.
	 * `NULL` by default.
	 * @var string
	 */
	protected $cssSupportingFile = \MvcCore\Ext\Forms\IForm::FORM_ASSETS_DIR_REPLACEMENT . '/fields/range.css';

	/**
	 * If range has multiple attribute, this function
	 * returns `array` of `float`s. If `Range` field has not `multiple` 
	 * attribute, this function returns `float`.
	 * If there is no value, function returns `NULL`.
	 * @return array|float|NULL
	 */
	public function GetValue () {
		return $this->value;
	}
	
	/**
	 * If range has multiple attribute, set to this function
	 * `array` of `float`s. If `Range` field has not `multiple` 
	 * attribute, set to this function `float`.
	 * If you don't want any pre initialized value, set `NULL`.
	 * @param array|float|NULL $value
	 * @return \MvcCore\Ext\Forms\Fields\Select
	 */
	public function & SetValue ($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method even if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Translate placeholder text if necessary.
	 * - Set up tabindex if necessary.
	 * - Add supporting javascript and css file.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		$this->form
			->AddJsSupportFile(
				$this->jsSupportingFile, 
				$this->jsClassName, 
				[$this->name . ($this->multiple ? '[]' : '')]
			)
			->AddCssSupportFile($this->cssSupportingFile);
	}
	
	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Forms\Field\Rendering` 
	 * in rendering process. Do not use this method even if you don't develop any form field.
	 * 
	 * Render control tag only without label or specific errors.
	 * @return string
	 */
	public function RenderControl () {
		$attrsStr = $this->renderControlAttrsWithFieldVars([
			'min', 'max', 'step',
			'list',
			'autoComplete',
			'placeHolder',
		]);
		if ($this->multiple) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'multiple="multiple"';
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'form="' . $this->form->GetId() . '"';
		$valueStr = $this->multiple && gettype($this->value) == 'array' 
			? implode(',', (array) $this->value) 
			: (string) $this->value;
		$valueStr = htmlspecialchars($valueStr, ENT_QUOTES);
		$formViewClass = $this->form->GetViewClass();
		$result = $formViewClass::Format(static::$templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name . ($this->multiple ? '[]' : ''),
			'type'		=> $this->type,
			'value'		=> $valueStr . '" data-value="' . $valueStr,
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
		return $this->renderControlWrapper($result);
	}
}
