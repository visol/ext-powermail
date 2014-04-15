<?php
namespace In2code\Powermail\ViewHelpers\Misc;

use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Returns Data-Attributes for JS Validator
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ValidationDataAttributeViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Errormessages
	 *
	 * @var array
	 */
	protected $errorMessages = array();

	/**
	 * Returns Data Attribute Array for JS validation with parsley.js
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \array $additionalAttributes To add further attributes
	 * @return \array for data attributes
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field, $additionalAttributes = array()) {
		$uriBuilder = $this->controllerContext->getUriBuilder();
		$dataArray = $additionalAttributes;
		$request = $this->controllerContext->getRequest();
		$extensionName = $request->getControllerExtensionName();
		if ($this->arguments['extensionName'] !== NULL) {
			$extensionName = $this->arguments['extensionName'];
		}

		// if mandatory field
		if ($field->getMandatory()) {
			//$dataArray['required'] = 'required';
			$dataArray['data-parsley-required'] = 'true';
			$dataArray['data-parsley-required-message'] = LocalizationUtility::translate('validationerror_mandatory', $extensionName);
		}

		// if validation
		if ($field->getValidation()) {
			$uri = $uriBuilder
				->setCreateAbsoluteUri(TRUE)
				->setArguments(
					array(
						'L' => $this->getLanguageUid(),
						'tx_powermail_pi1' => array(
							'mail' => array(
								'form' => $field->getPages()->getForms()->getUid()
							),
						),
						'eID' => 'powermailEidValidator'
					)
				)
				->build();

			$dataArray['data-parsley-remote'] = $uri;
			$dataArray['data-parsley-trigger'] = 'change';
			$dataArray['data-parsley-error-message'] = LocalizationUtility::translate(
				'validationerror_validation.' . $field->getValidation(),
				$extensionName
			);
		}
		return $dataArray;
	}

	/**
	 * Get Current FE language
	 *
	 * @return int
	 */
	protected function getLanguageUid() {
		return $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] ?
			$GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] : 0;
	}
}