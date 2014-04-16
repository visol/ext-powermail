<?php
namespace In2code\Powermail\ViewHelpers\Validation;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Returns Data-Attributes for JS and Native Validation
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ValidationDataAttributeViewHelper extends AbstractValidationViewHelper {

	/**
	 * Returns Data Attribute Array for JS validation with parsley.js
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \array $additionalAttributes To add further attributes
	 * @return \array for data attributes
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field, $additionalAttributes = array()) {
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
			$dataArray['data-parsley-required-message'] = LocalizationUtility::translate(
				'validationerror_mandatory',
				$extensionName
			);
		}

		// validation
		switch ($field->getValidation()) {

			/**
			 * EMAIL (+html5)
			 *
			 * html5 example: <input type="email" />
			 * javascript example: <input type="text" data-parsley-type="email" />
			 */
			case 1:
				if ($this->isClientValidationEnabled() && !$this->isNativeValidationEnabled()) {
					$dataArray['data-parsley-type'] = 'email';
				}
				break;

			/**
			 * URL (+html5)
			 *
			 * html5 example: <input type="url" />
			 * javascript example: <input type="text" data-parsley-type="url" />
			 */
			case 2:
				if ($this->isClientValidationEnabled() && !$this->isNativeValidationEnabled()) {
					$dataArray['data-parsley-type'] = 'url';
				}
				break;

			/**
			 * PHONE (+html5)
			 *
			 * html5 example:
			 * 		<input type="text" pattern="[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}" />
			 * javascript example:
			 * 		<input ... data-parsley-pattern="[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}" />
			 */
			case 3:
				if ($this->isNativeValidationEnabled()) {
					$dataArray['pattern'] = '[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}';
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-pattern'] = '[\+]\d{2}[\(]\d{2}[\)]\d{4}[\-]\d{4}';
					}
				}
				break;

			/**
			 * NUMBER/INTEGER (+html5)
			 *
			 * html5 example: <input type="number" />
			 * javascript example: <input type="text" data-parsley-type="integer" />
			 */
			case 4:
				if ($this->isClientValidationEnabled() && !$this->isNativeValidationEnabled()) {
					$dataArray['data-parsley-type'] = 'integer';
				}
				break;

			/**
			 * LETTERS (+html5)
			 *
			 * html5 example: <input type="text" pattern="[a-zA-Z]*" />
			 * javascript example: <input type="text" data-parsley-pattern="[a-zA-Z]*" />
			 */
			case 5:
				if ($this->isNativeValidationEnabled()) {
					$dataArray['pattern'] = '[A-Za-z]{3}';
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-pattern'] = '[a-zA-Z]*';
					}
				}
				break;

			/**
			 * MIN NUMBER (+html5)
			 *
			 * Note: Field validation_configuration for editors viewable
			 * html5 example: <input type="text" min="6" />
			 * javascript example: <input type="text" data-parsley-min="6" />
			 */
			case 6:
				if ($this->isNativeValidationEnabled()) {
					$dataArray['min'] = $field->getValidationConfiguration();
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-min'] = $field->getValidationConfiguration();
					}
				}
				break;

			/**
			 * MAX NUMBER (+html5)
			 *
			 * Note: Field validation_configuration for editors viewable
			 * html5 example: <input type="text" max="12" />
			 * javascript example: <input type="text" data-parsley-max="12" />
			 */
			case 7:
				if ($this->isNativeValidationEnabled()) {
					$dataArray['max'] = $field->getValidationConfiguration();
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-max'] = $field->getValidationConfiguration();
					}
				}
				break;

			/**
			 * RANGE (+html5)
			 *
			 * Note: Field validation_configuration for editors viewable
			 * html5 example: <input type="range" min="1" max="10" />
			 * javascript example:
			 * 		<input type="text" data-parsley-type="range" min="1" max="10" />
			 */
			case 8:
				$values = GeneralUtility::trimExplode(',', $field->getValidationConfiguration(), TRUE);
				if (intval($values[0]) <= 0 || intval($values[1]) <= 0) {
					break;
				}
				if ($this->isNativeValidationEnabled()) {
					$dataArray['min'] = intval($values[0]);
					$dataArray['max'] = intval($values[1]);
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-min'] = intval($values[0]);
						$dataArray['data-parsley-max'] = intval($values[1]);
					}
				}
				break;

			/**
			 * LENGTH
			 *
			 * Note: Field validation_configuration for editors viewable
			 * javascript example:
			 * 		<input type="text" data-parsley-length="[6, 10]" />
			 */
			case 9:
				$values = GeneralUtility::trimExplode(',', $field->getValidationConfiguration(), TRUE);
				if (intval($values[0]) <= 0) {
					break;
				}
				if (!isset($values[1])) {
					$values[1] = intval($values[0]);
					$values[0] = 0;
				}
				if ($this->isClientValidationEnabled()) {
					$dataArray['data-parsley-length'] = '[' . implode(', ', $values) . ']';
				}
				break;

			/**
			 * PATTERN (+html5)
			 *
			 * Note: Field validation_configuration for editors viewable
			 * html5 example: <input type="text" pattern="\d+" />
			 * javascript example:
			 * 		<input type="text" data-parsley-pattern="\d+" />
			 */
			case 10:
				if ($this->isNativeValidationEnabled()) {
					$dataArray['pattern'] = $field->getValidationConfiguration();
				} else {
					if ($this->isClientValidationEnabled()) {
						$dataArray['data-parsley-pattern'] = $field->getValidationConfiguration();
					}
				}
				break;

			default:
		}

		// set errormessage if javascript validation active
		if ($field->getValidation() && $this->isClientValidationEnabled()) {
			$dataArray['data-parsley-error-message'] = LocalizationUtility::translate(
				'validationerror_validation.' . $field->getValidation(),
				$extensionName
			);
		}

		// remote validation
//		if ($field->getValidation()) {
//			$uriBuilder = $this->controllerContext->getUriBuilder();
//			$uri = $uriBuilder
//				->setCreateAbsoluteUri(TRUE)
//				->setArguments(
//					array(
//						'L' => $this->getLanguageUid(),
//						'tx_powermail_pi1' => array(
//							'mail' => array(
//								'form' => $field->getPages()->getForms()->getUid()
//							),
//						),
//						'eID' => 'powermailEidValidator'
//					)
//				)
//				->build();
//
//			$dataArray['data-parsley-remote'] = $uri;
//			$dataArray['data-parsley-trigger'] = 'change';
//			$dataArray['data-parsley-error-message'] = LocalizationUtility::translate(
//				'validationerror_validation.' . $field->getValidation(),
//				$extensionName
//			);
//		}

		return $dataArray;
	}
}