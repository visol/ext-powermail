<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Returns CSS Classes for JS Validator
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ValidationClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Validation array
	 *
	 * @var validation
	 */
	protected $validationArray = array(
		1 => 'email',
		2 => 'url',
		3 => 'phone',
		4 => 'integer',
		5 => 'onlyLetterSp',
	);

	/**
	 * Returns CSS Class for JS validation
	 * e.g. validate[required,custom[email]]
	 * http://www.position-relative.net/creation/
	 * 		formValidator/demos/demoValidators.html
	 * http://www.position-absolute.com/articles/
	 * 		jquery-form-validator-because-form-validation-is-a-mess/
	 *
	 * @param 	object		Current field
	 * @return 	string		CSS Class
	 */
	public function render($field) {
		if (!$field->getMandatory() && !$field->getValidation()) {
			return '';
		}

		$cssString = '';

		$cssString .= 'validate[';

		if ($field->getMandatory()) {
			// normal fields
			if ($field->getType() != 'check') {
				$cssString .= 'required';
				if ($field->getValidation()) {
					$cssString .= ',';
				}
			// checkbox
			} else {
//				$cssString .= 'minCheckbox[1]';
				$cssString .= 'funcCall[checkCheckboxes]';
			}
		}

		if ($field->getValidation()) {
			$cssString .= 'custom[' . $this->validationArray[$field->getValidation()] . ']';
		}

		$cssString .= ']';

		return $cssString;
	}

}
