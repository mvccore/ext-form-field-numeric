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

namespace MvcCore\Ext\Forms\Field\Props;

/**
 * Trait for classes:
 * - `\MvcCore\Ext\Forms\Fields\Number`
 *    - `\MvcCore\Ext\Forms\Fields\Range`
 * Trait contains properties, getters and setters for 
 * protected properties `min`, `max` and `step`.
 * @mixin \MvcCore\Ext\Forms\Field
 */
trait MinMaxStepNumbers {

	/**
	 * Minimum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @var int|float|NULL
	 */
	protected $min = NULL;

	/**
	 * Maximum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @var int|float|NULL
	 */
	protected $max = NULL;

	/**
	 * Step value for `Number` in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @var int|float|string|NULL
	 */
	protected $step = NULL;

	/**
	 * Get minimum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @return int|float|NULL
	 */
	public function GetMin () {
		return $this->min;
	}

	/**
	 * Set minimum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @param  int|float|NULL $min
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetMin ($min) {
		if ($min !== NULL) 
			$this->min = is_numeric($min) ? $min : floatval($min);
		return $this;
	}

	/**
	 * Get maximum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @return int|float|NULL
	 */
	public function GetMax () {
		return $this->max;
	}

	/**
	 * Set maximum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @param  int|float|NULL $max
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetMax ($max) {
		if ($max !== NULL) 
			$this->max = is_numeric($max) ? $max : floatval($max);
		return $this;
	}

	/**
	 * Get step value for `Number` in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @return int|float|string|NULL
	 */
	public function GetStep () {
		return $this->step;
	}

	/**
	 * Set step value for `Number` in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @param  int|float|string|NULL $step
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetStep ($step) {
		if ($step !== NULL) 
			$this->step = is_numeric($step) 
				? $step 
				: ($step === 'any' ? 'any' : floatval($step));
		return $this;
	}
}
