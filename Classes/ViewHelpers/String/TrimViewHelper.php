<?php
namespace In2code\Powermail\ViewHelpers\String;

/**
 * Trim Inner HTML
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class TrimViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Trim Inner HTML
	 *
	 * @return bool
	 */
	public function render() {
		$string = trim($this->renderChildren());
		$string = preg_replace('/\\s\\s+/', ' ', $string);
		$string = str_replace(array('"; "', '" ; "', '" ;"'), '";"', $string);
		$string = str_replace(array('<br />', '<br>', '<br/>'), "\n", $string);
		$string = str_replace(array(" \n ", "\n ", " \n"), "\n", $string);

		return $string;
	}
}