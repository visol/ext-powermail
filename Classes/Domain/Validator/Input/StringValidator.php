<?php
namespace In2code\Powermail\Domain\Validator\Input;

class StringValidator extends \In2code\Powermail\Domain\Validator\AbstractValidator {

	/**
	 * Mandatory Check
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field Current field
	 * @param \In2code\Powermail\Domain\Model\Mail $mail Whole mail object
	 * @return bool
	 */
	protected function validateMandatory(\In2code\Powermail\Domain\Model\Field $field, \In2code\Powermail\Domain\Model\Mail $mail) {
		foreach ($mail->getAnswers() as $answer) {
			if ($answer->getField()->getUid() === $field->getUid()) {
				if (!is_array($answer->getValue())) { // default fields
					if ($answer->getValue()) {
						return TRUE;
					}
				} else { // checkboxes
					$filled = FALSE;
					foreach ($answer->getValue() as $subValue) {
						if (strlen($subValue)) {
							$filled = TRUE;
							break;
						}
					}
					if ($filled) {
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Must be there because of the interface
	 */
	public function isValid($value) {
		parent::isValid($value);
	}
}