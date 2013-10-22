<?php
namespace In2code\Powermail\ViewHelpers\Condition;

/**
 * View helper check if given value is number or not
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class IsThereAMailWithStartingLetterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * View helper check if given value is number or not
	 *
	 * @param 	object 		Current Mail Query
	 * @param 	string 		Starting Letter to search for
	 * @param 	int 		Field Uid
	 * @return 	boolean
	 */
	public function render($mails, $letter, $answerField) {
		foreach ($mails as $mail) {
			foreach ($mail->getAnswers() as $answer) {
				if ($answer->getField() == $answerField) {
					$value = $answer->getValue();
					if (strtolower($value[0]) == strtolower($letter)) {
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}
}