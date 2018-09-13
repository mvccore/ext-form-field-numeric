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
 * Responsibility: define single public function `ParseFloat($rawInput):float`
 *				   to parse raw user input into float value by locale configuration.
 * Interface for classes:
 * - `\MvcCore\Ext\Forms\Fields\Number`
 *    - `\MvcCore\Ext\Forms\Fields\Range`
 * - `\MvcCore\Ext\Forms\Validators\Number`
 */
interface INumber
{
	/**
	 * Try to parse floating point number from raw user input string.
	 * If `Intl` extension installed and if `Intl` extension parsing prefered, 
	 * try to parse by `Intl` extension integer first, than floating point number.
	 * If not prefered or not installed, try to determinate floating point in 
	 * user input string automaticly and use PHP `floatval()` to parse the result.
	 * If parsing by floatval returns `NULL` and `Intl` extension is installed
	 * but not prefered, try to parse user input by `Intl` extension after it.
	 * @param string $rawInput 
	 * @return float|NULL
	 */
	public function ParseFloat ($rawInput);
}
