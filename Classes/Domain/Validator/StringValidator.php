<?php
namespace In2code\Powermail\Domain\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class StringValidator extends \In2code\Powermail\Domain\Validator\AbstractValidator {

	/**
	 * Mandatory Check
	 *
	 * @param \mixed $value Fieldvalue from user
	 * @return bool
	 */
	protected function validateMandatory($value) {
		if (!is_array($value)) { // default fields
			if (!empty($value)) {
				return TRUE;
			}
		} else { // checkboxes
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
		if (preg_replace('/[^0-9+ .]/', '', $value) !== $value) {
			return FALSE;
		}
		return TRUE;
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
	 * Test string if there are only numbers
	 *
	 * @param \string $value
	 * @return bool
	 */
	protected function validateLettersOnly($value) {
		if (preg_replace('/[^a-zA-Z]/', '', $value) !== $value) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Must be there because of the interface
	 */
	public function isValid($value) {
		parent::isValid($value);
	}
}