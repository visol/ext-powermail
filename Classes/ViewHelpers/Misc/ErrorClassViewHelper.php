<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Returns Error Class if Error in form
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ErrorClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Prefill string for fields
	 *
	 * @param 	object		Current field
	 * @param 	string 		Any string for errorclass
	 * @return 	string		Changed string
	 */
	public function render($field, $class) {
		$validationResults = $this->controllerContext->getRequest()->getOriginalRequestMappingResults();
		$errors = $validationResults->getFlattenedErrors();
		foreach ($errors as $error) {
			foreach ((array) $error as $singleError) {
				if ($field->getMarker() === $singleError->getCode()) {
					return $class;
				}
			}
		}
		return '';
	}
}