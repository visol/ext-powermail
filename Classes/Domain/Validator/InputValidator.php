<?php
namespace In2code\Powermail\Domain\Validator;

use \In2code\Powermail\Domain\Model\Field;

class InputValidator extends \In2code\Powermail\Domain\Validator\StringValidator {

	/**
	 * Validation of given Params
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		// iterate through all fields of current form
		foreach ($mail->getForm()->getPages() as $page) { // every page
			foreach ($page->getFields() as $field) { // every field
				foreach ($mail->getAnswers() as $answer) { // iterate through answers of given mail object
					if ($answer->getField()->getUid() === $field->getUid()) {
						$this->isValidField($field, $answer->getValue());
					}
				}
			}
		}

		return $this->getIsValid();
	}

	/**
	 * Validate a single field
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \mixed $value
	 * @param $field
	 * @return void
	 */
	public function isValidField(Field $field, $value) {

		// Mandatory Check
		if ($field->getMandatory()) {
			if (!$this->validateMandatory($value)) {
				$this->setErrorAndMessage($field, 'mandatory');
			}
		}

		// String Checks
		switch ($field->getValidation()) {

			// email
			case 1:
				if (!$this->validateEmail($value)) {
					$this->setErrorAndMessage($field, 'validation.1');
				}
				break;

			// URL
			case 2:
				if (!$this->validateUrl($value)) {
					$this->setErrorAndMessage($field, 'validation.2');
				}
				break;

			// phone
			case 3:
				if (!$this->validatePhone($value)) {
					$this->setErrorAndMessage($field, 'validation.3');
				}
				break;

			// numbers only
			case 4:
				if (!$this->validateNumbersOnly($value)) {
					$this->setErrorAndMessage($field, 'validation.4');
				}
				break;

			// letters only
			case 5:
				if (!$this->validateLettersOnly($value)) {
					$this->setErrorAndMessage($field, 'validation.5');
				}
				break;

			default:
				// e.g. "custom" => search for method validateCustom()
				$validation = $field->getValidation();
				if (method_exists($this, 'validate' . ucfirst($validation))) {
					if (!$this->{'validate' . ucfirst($validation)}($value)) {
						$this->setErrorAndMessage($field, $validation); // message from locallang - "validationerror_custom"
					}
				}
				break;
		}
	}
}