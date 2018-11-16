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
 * Responsibility: init, pre-dispatch and render `<input>` HTML element 
 *				   with types `number` and type `range` in extended class. 
 *				   `Number` field and it's extended fields have their own 
 *				   validator(s) to parse and check submitted value by
 *				   min/max/step/pattern. This field always 
 *				   return parsed `float` or `NULL`.
 */
class		Number 
extends		\MvcCore\Ext\Forms\Field 
implements	\MvcCore\Ext\Forms\Fields\IVisibleField, 
			\MvcCore\Ext\Forms\Fields\ILabel,
			\MvcCore\Ext\Forms\Fields\IMinMaxStepNumbers,
			\MvcCore\Ext\Forms\Fields\IDataList
{
	use \MvcCore\Ext\Forms\Field\Props\VisibleField;
	use \MvcCore\Ext\Forms\Field\Props\Label;
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepNumbers;
	use \MvcCore\Ext\Forms\Field\Props\DataList;
	use \MvcCore\Ext\Forms\Field\Props\AutoComplete;
	use \MvcCore\Ext\Forms\Field\Props\PlaceHolder;
	use \MvcCore\Ext\Forms\Field\Props\Wrapper;
	use \MvcCore\Ext\Forms\Field\Props\InputMode;

	/**
	 * Possible values: `number` and `range` in extended class.
	 * @var string
	 */
	protected $type = 'number';
	
	/**
	 * Validators: 
	 * - `Number` - to parse and check raw user input. Parse float value if possible 
	 *				by `Intl` extension or try to determinate floating point automatically 
	 *				and return `float` or `NULL`.
	 * @var string[]|\Closure[]
	 */
	protected $validators = ['Number'];

	/**
	 * Numeric field value is always stored as float value.
	 * @var float|NULL
	 */
	protected $value = NULL;

	/**
	 * Get numeric field value as `float`.
	 * @return float|NULL
	 */
	public function GetValue () {
		return $this->value;
	}

	/**
	 * Set numeric field value as `float`.
	 * @param float|NULL $value 
	 * @return \MvcCore\Ext\Forms\Validators\Number
	 */
	public function & SetValue ($value) {
		$this->value = $value;
		return $this;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` after field
	 * is added into form instance by `$form->AddField();` method. Do not 
	 * use this method even if you don't develop any form field.
	 * - Check if field has any name, which is required.
	 * - Set up form and field id attribute by form id and field name.
	 * - Set up required.
	 * - Set up translate boolean property.
	 * - Set up pattern validator uatomaticly if any `pattern` property defined.
	 * @param \MvcCore\Ext\Form|\MvcCore\Ext\Forms\IForm $form
	 * @throws \InvalidArgumentException
	 * @return \MvcCore\Ext\Forms\Fields\Number|\MvcCore\Ext\Forms\IField
	 */
	public function & SetForm (\MvcCore\Ext\Forms\IForm & $form) {
		parent::SetForm($form);
		$this->setFormPattern();
		return $this;
	}

	/**
	 * Return field specific data for validator.
	 * @param array $fieldPropsDefaultValidValues 
	 * @return array
	 */
	public function & GetValidatorData ($fieldPropsDefaultValidValues = []) {
		$result = [
			'min'		=> $this->min, 
			'max'		=> $this->max, 
			'step'		=> $this->step,
		];
		if ($this->list !== NULL) {
			$result['list'] = $this->list;
			$listField = $this->form->GetField($this->list);
			if ($listField instanceof \MvcCore\Ext\Forms\Fields\IOptions) 
				$result['options'] = $listField->GetOptions();
		}
		return $result;
	}

	/**
	 * This INTERNAL method is called from `\MvcCore\Ext\Form` just before
	 * field is naturally rendered. It sets up field for rendering process.
	 * Do not use this method even if you don't develop any form field.
	 * - Set up field render mode if not defined.
	 * - Translate label text if necessary.
	 * - Set up `inputmode` field attribute if necessary.
	 * - Set up tab-index if necessary.
	 * @return void
	 */
	public function PreDispatch () {
		parent::PreDispatch();
		$this->preDispatchInputMode();
		$this->preDispatchTabIndex();
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
			'inputMode',
		]);
		if (!$this->form->GetFormTagRenderingStatus()) 
			$attrsStr .= (strlen($attrsStr) > 0 ? ' ' : '')
				. 'form="' . $this->form->GetId() . '"';
		$formViewClass = $this->form->GetViewClass();
		$result = $formViewClass::Format(static::$templates->control, [
			'id'		=> $this->id,
			'name'		=> $this->name,
			'type'		=> $this->type,
			'value'		=> htmlspecialchars_decode(htmlspecialchars($this->value, ENT_QUOTES), ENT_QUOTES),
			'attrs'		=> strlen($attrsStr) > 0 ? ' ' . $attrsStr : '',
		]);
		return $this->renderControlWrapper($result);
	}
}
