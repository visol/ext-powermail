<?php
namespace In2code\Powermail\ViewHelpers\Condition;

/**
 * Is {outer.{inner}} a datetime?
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class IsDateTimeVariableInVariableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Is {outer.{inner}} a datetime?
	 *
	 * @param	$obj	object Object
	 * @param	$prop	string Property
	 * @return	bool
	 */
	public function render($obj, $prop) {
		if (is_object($obj) && method_exists($obj, 'get' . \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($prop))) {
			$mixed = $obj->{'get' . \TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($prop)}();
		}
		return method_exists($mixed, 'getTimestamp');
	}
}