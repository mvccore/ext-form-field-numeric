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
class Float extends \MvcCore\Ext\Forms\Validators\Number
{
	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_FLOAT = 5;

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_FLOAT	=> "Field `{0}` requires a valid float number.",
	];

	/**
	 * Validate raw user input. Parse float value if possible by `Intl` extension 
	 * or try to determinate floating point automaticly and return `float` or `NULL`.
	 * @param string|array			$submitValue Raw user input.
	 * @return float|NULL	Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		$result = $this->parseFloat((string)$rawSubmittedValue);
		if ($result === NULL) 
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_FLOAT)
			);
		return $result;
	}
}
