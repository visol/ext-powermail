<?php
class Tx_Powermail_Domain_Validator_CaptchaValidator extends Tx_Powermail_Domain_Validator_AbstractValidator {

	/**
	 * fieldsRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_FieldRepository
	 */
	protected $fieldsRepository;

	/**
	 * formRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_FormRepository
	 */
	protected $formRepository;

	/**
	 * Captcha Session clean (only if mail is out)
	 *
	 * @var bool
	 */
	protected $clearSession;

	/**
	 * Return variable
	 *
	 * @var bool
	 */
	protected $isValid = true;

	/**
	 * Captcha Field found
	 *
	 * @var bool
	 */
	protected $captchaFound = false;

	/**
	 * Validation of given Captcha fields
	 *
	 * @param $params
	 * @return bool
	 */
	public function isValid($params) {
		if (!$this->formHasCaptcha()) {
			return $this->isValid;
		}

		foreach ((array) $params as $uid => $value) {
			// get current field values
			$field = $this->fieldsRepository->findByUid($uid);
			if (!method_exists($field, 'getUid')) {
				continue;
			}

			// if not a captcha field
			if ($field->getType() != 'captcha') {
				continue;
			}

			// if field wrong code given - set error
			$captcha = $this->objectManager->get('Tx_Powermail_Utility_CalculatingCaptcha');
			if (!$captcha->validCode($value, $this->clearSession)) {
				$this->addError('captcha', $uid);
				$this->isValid = false;
			}

			// Captcha field found
			$this->captchaFound = true;
		}

		if ($this->captchaFound) {
			return $this->isValid;
		} else {
			// if no captcha vars given
			$this->addError('captcha', 0);
			return false;
		}
  	}

	/**
	 * Checks if given form has a captcha
	 */
	protected function formHasCaptcha() {
		$gp = t3lib_div::_GP('tx_powermail_pi1');
		$formUid = $gp['form'];
		$form = $this->formRepository->hasCaptcha($formUid);
		return count($form);
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$piVars = t3lib_div::_GET('tx_powermail_pi1');
		$this->clearSession = ($piVars['action'] == 'create' ? true : false); // clear captcha on create action
	}

	/**
	 * injectFieldRepository
	 *
	 * @param Tx_Powermail_Domain_Repository_FieldRepository $fieldsRepository
	 * @return void
	 */
	public function injectFieldRepository(Tx_Powermail_Domain_Repository_FieldRepository $fieldsRepository) {
		$this->fieldsRepository = $fieldsRepository;
	}

	/**
	 * injectFormRepository
	 *
	 * @param Tx_Powermail_Domain_Repository_FormRepository $formRepository
	 * @return void
	 */
	public function injectFormRepository(Tx_Powermail_Domain_Repository_FormRepository $formRepository) {
		$this->formRepository = $formRepository;
	}
}
?>