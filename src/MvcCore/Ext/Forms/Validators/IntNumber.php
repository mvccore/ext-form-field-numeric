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
 * Responsibility: Validate raw user input. Parse integer value if possible by 
 *                 `Intl` extension or try to determinate floating point 
 *                 automatically ant then parse to int and return `int` or `NULL`.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class IntNumber extends \MvcCore\Ext\Forms\Validators\Number {

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
		self::ERROR_INT	=> "Field '{0}' requires a valid integer (from '{1}' to '{2}' incl.).",
	];

	/**
	 * Validate raw user input. Parse integer value if possible by `Intl` extension 
	 * or try to determinate floating point automatically and then parse to int and return `int` or `NULL`.
	 * @param  string|array $rawSubmittedValue Raw user input.
	 * @return int|NULL     Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		if (is_int($rawSubmittedValue) || is_float($rawSubmittedValue)) {
			if (is_nan($rawSubmittedValue))
				return NULL;
			$result = floatval($rawSubmittedValue);
		} else {
			$rawSubmittedValue = trim((string) $rawSubmittedValue);
			if (mb_strlen($rawSubmittedValue) === 0) return NULL;
			$result = $this->parseFloat($rawSubmittedValue);
			if ($result === NULL) {
				$this->validateAddErrorNoInt();
				return NULL;
			}
		}
		if (is_float($result)) {
			$resultInt = intval(round($result));
			$toolClass = static::$toolClass;
			if (!$toolClass::CompareFloats($result, floatval($resultInt))) {
				$this->validateAddErrorNoInt();
				return NULL;
			}
			$result = $resultInt;
		}
		$resultFloat = floatval($result);
		$this->validateMinMax($resultFloat);
		$this->validateStep($resultFloat);
		return $result;
	}

	/**
	 * Add validation error about invalid integer.
	 * @return void
	 */
	protected function validateAddErrorNoInt () {
		$min = $this->min === NULL ? -PHP_INT_MAX : $this->min;
		$max = $this->max === NULL ? PHP_INT_MAX : $this->max;
		$this->field->AddValidationError(
			static::GetErrorMessage(self::ERROR_INT, [$min, $max])
		);
	}
}
