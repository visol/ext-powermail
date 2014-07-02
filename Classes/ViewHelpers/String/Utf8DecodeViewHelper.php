<?php
namespace In2code\Powermail\ViewHelpers\String;

/**
 * utf8_decode for Inner HTML
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class Utf8DecodeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * utf8_decode for Inner HTML
	 *
	 * @return 	string
	 */
	public function render() {
		return utf8_decode($this->renderChildren());
	}
}