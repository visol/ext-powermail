<?php
namespace In2code\Powermail\ViewHelpers\Validation;

/**
 * Adds additional attributes for parsley or AJAX submit
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class EnableParsleyAndAjaxViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns Data Attribute Array to enable parsley
	 *
	 * @param \In2code\Powermail\Domain\Model\Form $form
	 * @param \array $additionalAttributes To add further attributes
	 * @return \array for data attributes
	 */
	public function render(\In2code\Powermail\Domain\Model\Form $form, $additionalAttributes = array()) {
		$dataArray = $additionalAttributes;

		// add parsley attribute
		if ($this->isClientValidationEnabled()) {
			$dataArray['data-parsley-validate'] = 'data-parsley-validate';
		}

		// add ajax attribute
		if ($this->settings['misc']['ajaxSubmit'] === '1') {
			$dataArray['data-powermail-ajax'] = $form->getUid();
		}

		return $dataArray;
	}
}