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
 */
trait MinMaxStepNumbers {

	/**
	 * Minimum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @var float|NULL
	 */
	protected $min = NULL;

	/**
	 * Maximum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @var float|NULL
	 */
	protected $max = NULL;

	/**
	 * Step value for `Number` in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @var float|NULL
	 */
	protected $step = NULL;

	/**
	 * Get minimum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @return float|NULL
	 */
	public function GetMin () {
		return $this->min;
	}

	/**
	 * Set minimum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @param  float|int|NULL $min
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetMin ($min) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		$this->min = $min === NULL ? NULL : floatval($min);
		return $this;
	}

	/**
	 * Get maximum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @return float|NULL
	 */
	public function GetMax () {
		return $this->max;
	}

	/**
	 * Set maximum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @param  float|int|NULL $max
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetMax ($max) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		$this->max = $max === NULL ? NULL : floatval($max);
		return $this;
	}

	/**
	 * Get step value for `Number` in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @return float|NULL
	 */
	public function GetStep () {
		return $this->step;
	}

	/**
	 * Set step value for `Number` in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @param  float|int|NULL $step
	 * @return \MvcCore\Ext\Forms\Field
	 */
	public function SetStep ($step) {
		/** @var $this \MvcCore\Ext\Forms\Field */
		$this->step = $step === NULL ? NULL : floatval($step);
		return $this;
	}
}
