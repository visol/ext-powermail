<?php
namespace In2code\Powermail\ViewHelpers\Validation;

/**
 * Returns Error Class if Error in form
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class EnableParsleyForFormViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns Data Attribute Array to enable parsley
	 *
	 * @param \array $additionalAttributes To add further attributes
	 * @return \array for data attributes
	 */
	public function render($additionalAttributes = array()) {
		$dataArray = $additionalAttributes;
		if ($this->isClientValidationEnabled()) {
			$dataArray['data-parsley-validate'] = 'data-parsley-validate';
		}
		return $dataArray;
	}
}