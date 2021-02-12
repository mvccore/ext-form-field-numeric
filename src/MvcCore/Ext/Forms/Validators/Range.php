<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate numeric raw user input. Parse numeric value or 
 *                 values by locale conventions and check minimum, maximum and 
 *                 step if necessary.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class Range extends \MvcCore\Ext\Forms\Validators\Number {

	use \MvcCore\Ext\Forms\Field\Props\Multiple;

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'multiple'	=> NULL,
	];

	/**
	 * Set up field instance, where is validated value by this 
	 * validator during submit before every `Validate()` method call.
	 * This method is also called once, when validator instance is separately 
	 * added into already created field instance to process any field checking.
	 * @param  \MvcCore\Ext\Forms\Field $field 
	 * @return \MvcCore\Ext\Forms\Validator
	 */
	public function SetField (\MvcCore\Ext\Forms\IField $field) {
		$this->field = $field;
		$this->setUpFieldProps(array_merge(
			self::$fieldSpecificProperties,
			parent::$fieldSpecificProperties
		));
		return $this;
	}
		
	/**
	 * Validate numeric raw user input. Parse numeric value or values by locale conventions
	 * and check minimum, maximum and step if necessary.
	 * @param  string|array      $rawSubmittedValue Raw user input.
	 * @return string|array|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$multiple = $this->field->GetMultiple();
		if ($multiple) {
			$rawSubmitValues = is_array($rawSubmittedValue) 
				? $rawSubmittedValue 
				: explode(',', (string) $rawSubmittedValue);
			$result = [];
			foreach ($rawSubmitValues as $rawSubmitValue) {
				$resultItem = parent::Validate($rawSubmitValue);
				if ($resultItem !== NULL) $result[] = $resultItem;
			}
			return $result;
		} else {
			return parent::Validate((string) $rawSubmittedValue);
		}
	}
}
