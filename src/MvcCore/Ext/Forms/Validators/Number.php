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

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate raw user input. Parse float value if possible by 
 *				   `Intl` extension or try to determinate floating point 
 *				   automaticly and return `float` or `NULL`.
 */
class Number 
	extends		\MvcCore\Ext\Forms\Validator
	implements	\MvcCore\Ext\Forms\Fields\IMinMaxStepNumbers
{
	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepNumbers;

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_NUMBER = 0;
	const ERROR_GREATER = 1;
	const ERROR_LOWER = 2;
	const ERROR_RANGE = 3;
	const ERROR_DIVISIBLE = 4;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_NUMBER		=> "Field `{0}` requires a valid number.",
		self::ERROR_GREATER		=> "Field `{0}` requires a value equal or greater than `{1}`.",
		self::ERROR_LOWER		=> "Field `{0}` requires a value equal or lower than `{1}`.",
		self::ERROR_RANGE		=> "Field `{0}` requires a value of `{1}` to `{2}` inclusive.",
		self::ERROR_DIVISIBLE	=> "Field `{0}` requires a divisible value of `{1}`.",
	];

	/**
	 * Set up field instance, where is validated value by this 
	 * validator durring submit before every `Validate()` method call.
	 * This method is also called once, when validator instance is separately 
	 * added into already created field instance to process any field checking.
	 * @param \MvcCore\Ext\Forms\Field|\MvcCore\Ext\Forms\IField $field 
	 * @return \MvcCore\Ext\Forms\Validator|\MvcCore\Ext\Forms\IValidator
	 */
	public function & SetField (\MvcCore\Ext\Forms\IField & $field) {
		parent::SetField($field);
		
		if (!$field instanceof \MvcCore\Ext\Forms\Fields\IMinMaxStepNumbers)
			$this->throwNewInvalidArgumentException(
				"Field `".$field->GetName()."` doesn't implement interface `\\MvcCore\\Ext\\Forms\\Fields\\IMinMaxStepNumbers`."
			);
		
		if (!$field instanceof \MvcCore\Ext\Forms\Fields\INumber)
			$this->throwNewInvalidArgumentException(
				"Field `".$field->GetName()."` doesn't implement interface `\\MvcCore\\Ext\\Forms\\Fields\\INumber`."
			);

		$fieldMin = $field->GetMin();
		if ($fieldMin !== NULL) {
			$this->min = $fieldMin;
		} else if ($this->min !== NULL && $fieldMin === NULL) {
			$field->SetMin($this->min);
		}
		$fieldMax = $field->GetMax();
		if ($fieldMax !== NULL) {
			$this->max = $fieldMax;
		} else if ($this->max !== NULL && $fieldMax === NULL) {
			$field->SetMax($this->max);
		}
		$fieldStep = $field->GetStep();
		if ($fieldStep !== NULL) {
			$this->step = $fieldStep;
		} else if ($this->step !== NULL && $fieldStep === NULL) {
			$field->SetStep($this->step);
		}

		return $this;
	}
	
	/**
	 * Validate raw user input. Parse float value if possible by `Intl` extension 
	 * or try to determinate floating point automaticly and return `float` or `NULL`.
	 * @param string|array			$submitValue Raw user input.
	 * @return string|array|NULL	Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = (string) $rawSubmittedValue;
		if (mb_strlen($rawSubmittedValue) === 0) return NULL;
		$result = $this->field->ParseFloat($rawSubmittedValue);
		if ($result === NULL) {
			$this->field->AddValidationError(
				static::GetErrorMessage(static::ERROR_NUMBER)
			);
			return NULL;
		}
		if (
			$this->min !== NULL && $this->max !== NULL &&
			$this->min > 0 && $this->max > 0 &&
			($result < $this->min || $result > $this->max)
		) {
			$this->field->AddValidationError(
				$this->form->GetDefaultErrorMsg(static::ERROR_RANGE),
				[$this->min, $this->max]
			);
		} else if ($this->min !== NULL && $this->min > 0 && $result < $this->min) {
			$this->field->AddValidationError(
				$this->form->GetDefaultErrorMsg(static::ERROR_GREATER),
				[$this->min]
			);
		} else if ($this->max !== NULL && $this->max > 0 && $result > $this->max) {
			$this->field->AddValidationError(
				$this->form->GetDefaultErrorMsg(static::ERROR_LOWER),
				[$this->max]
			);
		}
		if ($this->step !== NULL && $this->step !== 0) {
			$dividingResultFloat = floatval($result) / $this->step;
			$dividingResultInt = floatval(intval($dividingResultFloat));
			if ($dividingResultFloat !== $dividingResultInt) 
				$this->field->AddValidationError(
					$this->form->GetDefaultErrorMsg(static::ERROR_DIVISIBLE),
					[$this->step]
				);
		}
		return $result;
	}

}
