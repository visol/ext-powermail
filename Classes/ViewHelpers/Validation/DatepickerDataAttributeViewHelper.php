<?php
namespace In2code\Powermail\ViewHelpers\Validation;

use \TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Returns Data-Attributes for JS and Native Validation
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class DatepickerDataAttributeViewHelper extends AbstractValidationViewHelper {

	/**
	 * @var \string
	 */
	protected $extensionName;

	/**
	 * Returns Data Attribute Array Datepicker settings
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \array $additionalAttributes To add further attributes
	 * @return \array for data attributes
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field, $additionalAttributes = array()) {
		$this->extensionName = $this->controllerContext->getRequest()->getControllerExtensionName();

		$additionalAttributes['data-datepicker-force'] =
			$this->settings['misc']['datepicker']['forceJavaScriptDatePicker'];
		$additionalAttributes['data-datepicker-settings'] = $field->getDatepickerSettings();
		$additionalAttributes['data-datepicker-months'] = $this->getMonthNames();
		$additionalAttributes['data-datepicker-days'] = $this->getDayNames();
		$additionalAttributes['data-datepicker-format'] = $this->getFormat($field);

		return $additionalAttributes;
	}

	/**
	 * Get timeformat out of datepicker type
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @return string
	 */
	protected function getFormat(\In2code\Powermail\Domain\Model\Field $field) {
		return LocalizationUtility::translate('datepicker_format_' . $field->getDatepickerSettings(), $this->extensionName);
	}

	/**
	 * Generate Monthnames from locallang
	 *
	 * @return string
	 */
	protected function getDayNames() {
		$days = array(
			'so',
			'mo',
			'tu',
			'we',
			'th',
			'fr',
			'sa',
		);
		$dayArray = array();
		foreach ($days as $day) {
			$dayArray[] = LocalizationUtility::translate('datepicker_day_' . $day, $this->extensionName);
		}
		return implode(',', $dayArray);
	}

	/**
	 * Generate Monthnames from locallang
	 *
	 * @return string
	 */
	protected function getMonthNames() {
		$months = array(
			'jan',
			'feb',
			'mar',
			'apr',
			'may',
			'jun',
			'jul',
			'aug',
			'sep',
			'oct',
			'nov',
			'dec',
		);
		$monthArray = array();
		foreach ($months as $month) {
			$monthArray[] = LocalizationUtility::translate('datepicker_month_' . $month, $this->extensionName);
		}
		return implode(',', $monthArray);
	}
}