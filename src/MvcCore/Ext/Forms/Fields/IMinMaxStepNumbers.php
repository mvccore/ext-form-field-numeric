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
 * Responsibility: define getters and setters for field properties: `minOptions`, 
 *				   `maxOptions`, `minOptionsBubbleMessage` and `maxOptionsBubbleMessage`.
 * Interface for classes:
 * - `\MvcCore\Ext\Forms\Fields\Number`
 *    - `\MvcCore\Ext\Forms\Fields\Range`
 * - `\MvcCore\Ext\Forms\Validators\Number`
 */
interface IMinMaxStepNumbers
{
	/**
	 * Get minimum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @return float|NULL
	 */
	public function GetMin ();

	/**
	 * Set minimum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-min
	 * @param float|int|NULL $min
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetMin ($min);

	/**
	 * Get maximum value for `Number` field(s) in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @return float|NULL
	 */
	public function GetMax ();

	/**
	 * Set maximum value for `Number` field(s) in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-max
	 * @param float|NULL $max
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetMax ($max);

	/**
	 * Get step value for `Number` in `float`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @return float|NULL
	 */
	public function GetStep ();

	/**
	 * Set step value for `Number` in `float` or in `integer`.
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input#attr-step
	 * @param float|int|NULL $step
	 * @return \MvcCore\Ext\Forms\IField
	 */
	public function & SetStep ($step);
}
