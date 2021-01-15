<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Forms\Validators;

/**
 * Responsibility: Validate raw user input. Parse integer value if possible by 
 *				   `Intl` extension or try to determinate floating point 
 *				   automatically ant then parse to int and return `int` or `NULL`.
 */
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
		self::ERROR_INT	=> "Field `{0}` requires a valid integer (from `{1}` to `{2}` incl.).",
	];

	/**
	 * Validate raw user input. Parse integer value if possible by `Intl` extension 
	 * or try to determinate floating point automatically and then parse to int and return `int` or `NULL`.
	 * @param string|array	$submitValue Raw user input.
	 * @return int|NULL		Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$rawSubmittedValue = trim((string) $rawSubmittedValue);
		if (mb_strlen($rawSubmittedValue) === 0) return NULL;
		$result = $this->parseFloat($rawSubmittedValue);
		if ($result === NULL) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_INT)
			);
			return NULL;
		} else {
			$resultInt = intval(round($result));
			if (!$this->compareFloats($result, floatval($resultInt))) {
				$min = $this->min === NULL ? -PHP_INT_MAX : $this->min;
				$max = $this->max === NULL ? PHP_INT_MAX : $this->max;
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_INT, [$min, $max])
				);
				return NULL;
			} else {
				return $resultInt;
			}
		}
	}

	/**
	 * Return `TRUE` if given floats are absolutelly equal.
	 * @param float $a (required) Left operand
	 * @param float $b (required) Right operand
	 * @return bool
	 */
	protected function compareFloats ($a, $b) {
		$aInt = intval($a);
		$bInt = intval($b);

		$aIntLen = strlen(strval($aInt));
		$bIntLen = strlen(strval($bInt));

		$aStr = strval($a);
		$bStr = strval($b);
		if ($aStr === '') $aStr = '0';
		if ($bStr === '') $bStr = '0';

		if (strpos($aStr, '.') === FALSE) $aStr .= '.0';
		if (strpos($bStr, '.') === FALSE) $bStr .= '.0';

		$aStrLen = strlen($aStr);
		$bStrLen = strlen($bStr);

		$aPreciseLen = $aStrLen - $aIntLen - 1;
		$bPreciseLen = $bStrLen - $bIntLen - 1;
		$maxPreciseLen = max($aPreciseLen, $bPreciseLen);

		return bccomp($a, $b, $maxPreciseLen) === 0;
	}
}
