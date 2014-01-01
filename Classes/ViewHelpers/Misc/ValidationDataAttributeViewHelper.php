<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Returns Data-Attributes for JS Validator
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ValidationDataAttributeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Returns Data Attribute Array for JS validation with parsley.js
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @return array for data attribute
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field) {
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$dataArray = array();
		if ($field->getMandatory()) {
			$request = $this->controllerContext->getRequest();
			$extensionName = $this->arguments['extensionName'] === NULL ? $request->getControllerExtensionName() : $this->arguments['extensionName'];
			$message = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('validationerror_mandatory', $extensionName);
			//$dataArray['required'] = 'required';
			$dataArray['data-required'] = 'true';
			$dataArray['data-error-message'] = $message;
		}
		if ($field->getValidation()) {
			$languageUid = ($GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] ? $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] : 0);
			$uri = $uriBuilder
				->setCreateAbsoluteUri(TRUE)
				->setArguments(
					array(
						'L' => $languageUid,
						'tx_powermail_pi1' => array(
							'mail' => array(
								'form' => $field->getPages()->getForms()->getUid()
							),
						),
						'eID' => 'powermailEidValidator'
					)
				)
				->build();
			$dataArray['data-remote'] = $uri; // sending url for AJAX request
			$dataArray['data-trigger'] = 'change'; // must be change because of a bug in parsley.js with async remote validation
		}
		return $dataArray;
	}

}