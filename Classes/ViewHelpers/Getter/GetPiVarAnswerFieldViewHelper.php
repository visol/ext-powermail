<?php
namespace In2code\Powermail\ViewHelpers\Getter;

/**
 * Used in the Backendmodule to get a defined piVar
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class GetPiVarAnswerFieldViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Used in the Backendmodule to get a defined piVar
	 *
	 * @param 	int			Field UID
	 * @param 	array		Plugin Vars
	 * @return	string		parsed Variable
	 */
	public function render($fieldUid, $piVars) {
		if (isset($piVars['filter']['answer'][$fieldUid])) {
			$result = htmlspecialchars($piVars['filter']['answer'][$fieldUid]);
		}

		return $result;
	}
}