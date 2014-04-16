<?php
namespace In2code\Powermail\Domain\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * StringValidator
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class StringValidator extends \In2code\Powermail\Domain\Validator\AbstractValidator {

	/**
	 * Mandatory Check
	 *
	 * @param \mixed $value Fieldvalue from user
	 * @return bool
	 */
	protected function validateMandatory($value) {
		// default fields
		if (!is_array($value)) {
			if (!empty($value)) {
				return TRUE;
			}
		// checkboxes
		} else {
			$filled = FALSE;
			foreach ($value as $subValue) {
				if (strlen($subValue)) {
					$filled = TRUE;
					break;
				}
			}
			if ($filled) {
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Test string if valid email
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validateEmail($value) {
		return GeneralUtility::validEmail($value);
	}

	/**
	 * Test string if its an URL
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validateUrl($value) {
		return filter_var($value, FILTER_VALIDATE_URL);
	}

	/**
	 * Test string if its a phone number
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validatePhone($value) {
		if (preg_replace('/[^0-9+ .]/', '', $value) === $value) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test string if there are only numbers
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validateNumbersOnly($value) {
		return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
	}

	/**
	 * Test string if there are only letters
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validateLettersOnly($value) {
		if (preg_replace('/[^a-zA-Z]/', '', $value) === $value) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test if number is greater than configuration
	 *
	 * @param \string $value
	 * @param \string $configuration e.g. "4"
	 * @return bool
	 */
	protected function validateMinNumber($value, $configuration) {
		if ($value >= $configuration) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test if number is less than configuration
	 *
	 * @param \string $value
	 * @param \string $configuration e.g. "4"
	 * @return bool
	 */
	protected function validateMaxNumber($value, $configuration) {
		if ($value <= $configuration) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test if number is in range
	 *
	 * @param \string $value
	 * @param \string $configuration e.g. "1,6" or "6"
	 * @return bool
	 */
	protected function validateRange($value, $configuration) {
		$values = GeneralUtility::trimExplode(',', $configuration, TRUE);
		if (intval($values[0]) <= 0) {
			return TRUE;
		}
		if (!isset($values[1])) {
			$values[1] = $values[0];
			$values[0] = 1;
		}
		if ($value >= $values[0] || $value <= $values[1]) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test if stringlength is in range
	 *
	 * @param \string $value
	 * @param \string $configuration e.g. "1,6" or "6"
	 * @return bool
	 */
	protected function validateLength($value, $configuration) {
		$values = GeneralUtility::trimExplode(',', $configuration, TRUE);
		if (intval($values[0]) <= 0) {
			return TRUE;
		}
		if (!isset($values[1])) {
			$values[1] = $values[0];
			$values[0] = 1;
		}
		if (strlen($value) >= $values[0] || strlen($value) <= $values[1]) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Test if value is ok with RegEx
	 *
	 * @param \string $value
	 * @param \string $configuration e.g. "https?://.+"
	 * @return bool
	 */
	protected function validatePattern($value, $configuration) {
		if (preg_match('~' . $configuration . '~', $value)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Must be there because of the interface
	 *
	 * @param \string $value
	 * @return void
	 */
	public function isValid($value) {
		parent::isValid($value);
	}
}