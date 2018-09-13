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
 * Responsibility: Validate raw user input. Parse integer value if possible by 
 *				   `Intl` extension or try to determinate floating point 
 *				   automaticly ant then parse to int and return `int` or `NULL`.
 */
class Integer extends \MvcCore\Ext\Forms\Validators\Number
{
	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_INT = 5;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_INT	=> "Field `{0}` requires a valid integer.",
	];

	/**
	 * Validate raw user input. Parse integer value if possible by `Intl` extension 
	 * or try to determinate floating point automaticly ant then parse to int and return `int` or `NULL`.
	 * @param string|array			$submitValue Raw user input.
	 * @return int|NULL	Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$result = $this->parseFloat((string)$rawSubmittedValue);
		if ($result === NULL) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_INT)
			);
			return NULL;
		} else {
			$resultInt = intval($result);
			if ($result !== floatval($resultInt)) {
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_INT)
				);
				return NULL;
			} else {
				return $resultInt;
			}
		}
	}
}
