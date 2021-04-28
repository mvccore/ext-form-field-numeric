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
		'min'		=> NULL, 
		'max'		=> NULL, 
		'step'		=> NULL,
		'multiple'	=> NULL,
	];

	
	/**
	 * Create range validator instance.
	 * 
	 * @param  array     $cfg
	 * Config array with protected properties and it's 
	 * values which you want to configure, presented 
	 * in camel case properties names syntax.
	 * 
	 * @param  int|float $min
	 * Minimum value for `Number` field(s) in `float` or in `integer`.
	 * @param  int|float $max
	 * Maximum value for `Number` field(s) in `float` or in `integer`.
	 * @param  int|float $step
	 * Step value for `Number` in `float` or in `integer`.
	 * 
	 * @param  bool      $multiple
	 * If control is `<input>` with `type` as `file` or `email`,
	 * this Boolean attribute indicates whether the user can enter 
	 * more than one value.
	 * If control is `<input>` with `type` as `range`, there are 
	 * rendered two connected sliders (range controls) as one control
	 * to simulate range from and range to. Result value will be array.
	 * If control is `<select>`, this Boolean attribute indicates 
	 * that multiple options can be selected in the list. When 
	 * multiple is specified, most browsers will show a scrolling 
	 * list box instead of a single line drop down.
	 * 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function __construct(
		array $cfg = [],
		$min = NULL,
		$max = NULL,
		$step = NULL,
		$multiple = NULL
	) {
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		parent::__construct($cfg);
	}

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
			foreach ($rawSubmitValues as $rawSubmitValue) 
				$result[] = parent::Validate($rawSubmitValue);
			return $result;
		} else {
			return parent::Validate((string) $rawSubmittedValue);
		}
	}
}
