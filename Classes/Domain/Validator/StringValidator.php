<?php
namespace In2code\Powermail\Domain\Validator;

class StringValidator extends \In2code\Powermail\Domain\Validator\AbstractValidator {

	/**
	 * regEx and filter array
	 * Note: PHP filters see http://php.net/manual/en/filter.filters.sanitize.php and http://de.php.net/manual/de/function.filter-var.php
	 *
	 * @var regEx
	 */
	protected $regEx = array(
		1 => FILTER_VALIDATE_EMAIL, // email
		2 => FILTER_VALIDATE_URL, // url
		3 => '/[^0-9+ .]/', // phone
		4 => FILTER_SANITIZE_NUMBER_INT, // numbers
		5 => '/[^a-zA-Z]/', // letters
	);

	/**
	 * Validation of given Params
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		foreach ($mail->getForm()->getPages() as $page) { // every page in current form
			foreach ($page->getFields() as $field) { // every field in current page

				// if validation of field or value empty
				if (!$field->getValidation()) {
					continue;
				}

				$this->isValidString($field, $mail);
			}
		}

		return $this->getIsValid();
	}

	/**
	 * Check if there is a value in mail.answers.x.field for every field
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	protected function isValidString(\In2code\Powermail\Domain\Model\Field $field, \In2code\Powermail\Domain\Model\Mail $mail) {
		foreach ($mail->getAnswers() as $answer) {
			if ($answer->getField()->getUid() === $field->getUid()) {
				if (is_numeric($this->regEx[$field->getValidation()])) { // filter
					if (filter_var($answer->getValue(), $this->regEx[$field->getValidation()]) === FALSE) { // check failed
						$this->setErrorAndMessage($field, 'validation');
					}
				} else { // regex
					if (preg_replace($this->regEx[$field->getValidation()], '', $answer->getValue()) !== $answer->getValue()) { // check failed
						$this->setErrorAndMessage($field, 'validation');
					}
				}
			}
		}
		return TRUE;
	}

}