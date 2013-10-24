<?php
namespace In2code\Powermail\Domain\Validator;

class InputValidator extends \In2code\Powermail\Domain\Validator\Input\StringValidator {

	/**
	 * Validation of given Params
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($mail);
		// iterate through all fields of current form
		foreach ($mail->getForm()->getPages() as $page) { // every page
			foreach ($page->getFields() as $field) { // every field

				// Mandatory Check
				if ($field->getMandatory()) {
					if (!$this->validateMandatory($field, $mail)) {
						$this->setErrorAndMessage($field, 'mandatory');
					}
				}

				// String Checks
				switch ($field->getValidation()) {
					case 1:
//						if (!$this->validateEmail($field, $mail)) {
//							$this->setErrorAndMessage($field, 'validation');
//						}
						break;

					case 2:
						break;

					case 3:
						break;

					case 4:
						break;

					case 5:
						break;
				}
			}
		}

		return $this->getIsValid();
	}
}