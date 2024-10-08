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
 * Responsibility: Validate raw user input. Parse float value if possible by 
 *                 `Intl` extension or try to determinate floating point 
 *                 automatically and return `float` or `NULL`.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class		Number 
extends		\MvcCore\Ext\Forms\Validator
implements	\MvcCore\Ext\Forms\Fields\IMinMaxStepNumbers {

	use \MvcCore\Ext\Forms\Field\Props\MinMaxStepNumbers;

	/**
	 * Error message index(es).
	 * @var int
	 */
	const ERROR_NUMBER = 0;
	const ERROR_GREATER = 1;
	const ERROR_LOWER = 2;
	const ERROR_RANGE = 3;
	const ERROR_DIVISIBLE = 4;

	const FLOAT_PARSER_INTERFACE = '\\MvcCore\\Ext\\Tools\\Locales\\IFloatParser';

	/**
	 * Validation failure message template definitions.
	 * @var array
	 */
	protected static $errorMessages = [
		self::ERROR_NUMBER		=> "Field '{0}' requires a valid number.",
		self::ERROR_GREATER		=> "Field '{0}' requires a value equal or greater than '{1}'.",
		self::ERROR_LOWER		=> "Field '{0}' requires a value equal or lower than '{1}'.",
		self::ERROR_RANGE		=> "Field '{0}' requires a value of '{1}' to '{2}' inclusive.",
		self::ERROR_DIVISIBLE	=> "Field '{0}' requires a divisible value of '{1}'.",
	];

	/**
	 * Field specific values (camel case) and their validator default values.
	 * @var array
	 */
	protected static $fieldSpecificProperties = [
		'min'	=> NULL, 
		'max'	=> NULL, 
		'step'	=> NULL,
	];

	/**
	 * Float parser class. Parser class has to implement 
	 * interface `\MvcCore\Ext\Tools\Locales\IFloatParser`.
	 * @var string
	 */
	protected $parserClass = '\\MvcCore\\Ext\\Tools\\Locales\\FloatParser';

	/**
	 * Float parser instance. Parser instance implements
	 * interface `\MvcCore\Ext\Tools\Locales\IFloatParser`.
	 * @var \MvcCore\Ext\Tools\Locales\FloatParser|NULL
	 */
	protected $floatParser = NULL;

	/**
	 * PHP runtime precision from `init_get('precision');` or `14` by default.
	 * @internal
	 * @var int|NULL
	 */
	protected $runtimePrecision = NULL;

	/**
	 * Field `step` attribute fractions length.
	 * @internal
	 * @var int|NULL
	 */
	protected $stepFractionsLength = NULL;
	

	/**
	 * Set float parser class. Parser class has to implement 
	 * interface `\MvcCore\Ext\Tools\Locales\IFloatParser`;
	 * @param  string $parserClass
	 * @return \MvcCore\Ext\Forms\Validators\Number
	 */
	public function SetParserClass ($parserClass) {
		$toolClass = \MvcCore\Application::GetInstance()->GetToolClass();
		$toolClass::CheckClassInterface($parserClass, static::FLOAT_PARSER_INTERFACE, TRUE, TRUE);
		$this->parserClass = $parserClass;
		return $this;
	}

	/**
	 * Get float parser class. Parser class has to implement 
	 * interface `\MvcCore\Ext\Tools\Locales\IFloatParser`;
	 * @return string
	 */
	public function GetParserClass () {
		return $this->parserClass;
	}
	
	/**
	 * Create number validator instance.
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
	 * @param  string    $parserClass
	 * Float parser class. Parser class has to implement 
	 * interface `\MvcCore\Ext\Tools\Locales\IFloatParser`;
	 * 
	 * @throws \InvalidArgumentException 
	 * @return void
	 */
	public function __construct(
		array $cfg = [],
		$min = NULL,
		$max = NULL,
		$step = NULL,
		$parserClass = NULL
	) {
		$errorMessages = static::$errorMessages;
		$this->consolidateCfg($cfg, func_get_args(), func_num_args());
		parent::__construct($cfg);
		if ($parserClass !== NULL)
			$this->SetParserClass($parserClass);
		if (self::$errorMessages !== $errorMessages)
			static::$errorMessages = array_replace(
				self::$errorMessages,
				$errorMessages
			);
	}
	
	/**
	 * Validate raw user input. Parse float value if possible by `Intl` extension 
	 * or try to determinate floating point automatically and return `float` or `NULL`.
	 * @param  string|array   $rawSubmittedValue Raw user input.
	 * @return int|float|NULL Safe submitted value or `NULL` if not possible to return safe value.
	 */
	public function Validate ($rawSubmittedValue) {
		if (is_float($rawSubmittedValue) || is_int($rawSubmittedValue)) {
			if (is_nan($rawSubmittedValue))
				return NULL;
			$result = floatval($rawSubmittedValue);
		} else {
			$rawSubmittedValue = (string) $rawSubmittedValue;
			if (mb_strlen($rawSubmittedValue) === 0) 
				return NULL;
			$result = $this->parseFloat($rawSubmittedValue);
			if ($result === NULL) {
				$this->validateAddErrorNoNumber();
				return NULL;
			}
		}
		$this->validateMinMax($result);
		$this->validateStep($result);
		return $result;
	}

	/**
	 * Parse raw user input by automatic floating point number detection.
	 * @param  string $rawSubmittedValue 
	 * @return int|float|NULL
	 */
	protected function parseFloat ($rawSubmittedValue) {
		return $this->getFloatParser()->Parse($rawSubmittedValue);
	}
	
	/**
	 * Add validation error about invalid number.
	 * @return void
	 */
	protected function validateAddErrorNoNumber () {
		$this->field->AddValidationError(
			static::GetErrorMessage(static::ERROR_NUMBER)
		);
	}

	/**
	 * Validate min and max attribute.
	 * @param  float $result 
	 * @return void
	 */
	protected function validateMinMax ($result) {
		if (
			$this->min !== NULL && $this->max !== NULL &&
			$this->min > 0 && $this->max > 0 &&
			($result < $this->min || $result > $this->max)
		) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_RANGE), [$this->min, $this->max]
			);
		} else if ($this->min !== NULL && $this->min > 0 && $result < $this->min) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_GREATER), [$this->min]
			);
		} else if ($this->max !== NULL && $this->max > 0 && $result > $this->max) {
			$this->field->AddValidationError(
				static::GetErrorMessage(self::ERROR_LOWER), [$this->max]
			);
		}
	}

	/**
	 * Validate step attribute.
	 * @param  float $result 
	 * @return void
	 */
	protected function validateStep ($result) {
		$toolClass = static::$toolClass;
		if ($this->step !== NULL && !$toolClass::CompareFloats(floatval($this->step), 0.0)) {
			// TODO: this must start from min value!
			$this->validateInitStepFractionsLength();
			$dividingResultFloat = floatval($result) / floatval($this->step);
			$dividingResultRound = round($dividingResultFloat);
			$relativeEpsilon = floatval('2.220446049250313E-' . ($this->stepFractionsLength + 1));
			if (abs($dividingResultFloat - $dividingResultRound) <= $relativeEpsilon) {
				// if number is 123456.9999999999 , turn it into 123457.0
				// if number is 123456.0000000001 , turn it into 123456.0
				$dividingResultFloat = $dividingResultRound;
			}
			$dividingResultInt = floatval(intval($dividingResultFloat));
			$resultIsPossible2DivideByStep = $toolClass::CompareFloats(
				$dividingResultFloat, $dividingResultInt
			);
			if (!$resultIsPossible2DivideByStep) {
				$this->field->AddValidationError(
					static::GetErrorMessage(self::ERROR_DIVISIBLE), [$this->step]
				);
			}
		}
	}

	/**
	 * Initialize step fractions length in submit validation.
	 * @return void
	 */
	protected function validateInitStepFractionsLength () {
		$runtimePrecision = $this->getRuntimePrecision();
		$this->stepFractionsLength = static::getFloatFractionsCount($this->step, $runtimePrecision);
	}

	/**
	 * Return initialized float parser instance.
	 * @return \MvcCore\Ext\Tools\Locales\FloatParser
	 */
	protected function getFloatParser () {
		if ($this->floatParser === NULL) {
			$parserClass = $this->parserClass;
			$this->floatParser = $parserClass::CreateInstance();
			if ($formLang = $this->form->GetLang()) 
				$this->floatParser->SetLang($formLang);
			if ($formLocale = $this->form->GetLocale()) 
				$this->floatParser->SetLocale($formLocale);
		}
		return $this->floatParser;
	}
	
	/**
	 * Get PHP runtime precision from `init_get('precision');` or `14` by default.
	 * @return int
	 */
	protected function getRuntimePrecision () {
		if ($this->runtimePrecision === NULL) {
			$runtimePrecision = @ini_get('precision');
			if ($runtimePrecision === FALSE) $runtimePrecision = 14;
			$this->runtimePrecision = $runtimePrecision;
		}
		return $this->runtimePrecision;
	}

	/**
	 * Get float number fractions count.
	 * @param  float $floatNumber
	 * @param  int   $runtimePrecision
	 * @return int
	 */
	protected static function getFloatFractionsCount ($floatNumber, $runtimePrecision) {
		list(, $stepFractionsStr) = explode('.', number_format($floatNumber, $runtimePrecision, '.', ''));
		$stepFractionsStr = rtrim($stepFractionsStr, '0');
		return strlen($stepFractionsStr);
	}
}
