<?php
class Tx_Powermail_Domain_Validator_MandatoryValidator extends Tx_Powermail_Domain_Validator_AbstractValidator {

	/**
	 * Mandatory Validation of given answers
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		foreach ($mail->getForm()->getPages() as $page) { // every page in current form
			foreach ($page->getFields() as $field) { // every field in current page

				// if not a mandatory field
				if (!$field->getMandatory()) {
					continue;
				}

				if (!$this->isValueSet($field, $mail)) {
					$this->setErrorAndMessage($field, 'mandatory');
				}
			}
		}
		return $this->getIsValid();
	}

	/**
	 * Check if there is a value in mail.answers.x.field for every field
	 *
	 * @param Tx_Powermail_Domain_Model_Field $field
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return bool
	 */
	protected function isValueSet(Tx_Powermail_Domain_Model_Field $field, Tx_Powermail_Domain_Model_Mail $mail) {
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

}