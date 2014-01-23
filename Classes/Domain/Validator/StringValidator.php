<?php
namespace In2code\Powermail\Domain\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * StringValidator
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * GNU Lesser General Public License, version 3 or later
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
	 *
	 * @return void
	 */
	public function isValid($value) {
		parent::isValid($value);
	}
}
