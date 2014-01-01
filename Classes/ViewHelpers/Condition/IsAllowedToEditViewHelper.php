<?php
namespace In2code\Powermail\ViewHelpers\Condition;

/**
 * Check if logged in User is allowed to edit
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class IsAllowedToEditViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Div Methods
	 *
	 * @var \In2code\Powermail\Utility\Div
	 *
	 * @inject
	 */
	protected $div;

	/**
	 * Check if logged in User is allowed to edit
	 *
	 * @param 	array 		TypoScript and FlexForm Settings
	 * @param 	object 		Mail Object
	 * @return 	boolean
	 */
	public function render($settings = array(), $mail) {
		if ($this->div->isAllowedToEdit($settings, $mail)) {
			return TRUE;
		}
		return FALSE;
	}

}