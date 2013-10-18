<?php

/**
 * View helper to generate select field with empty values, preselected, etc...
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class Tx_Powermail_ViewHelpers_Form_SelectFieldViewHelper extends Tx_Fluid_ViewHelpers_Form_SelectViewHelper {

	/**
	 * Render the tag.
	 *
	 * @return string rendered tag.
	 * @api
	 */
	public function render() {
		$content = parent::render();

		return $content;
	}

	/**
	 * Render the option tags.
	 *
	 * @param array $options the options for the form.
	 * @return string rendered tags.
	 */
	protected function renderOptionTags($options) {
		$output = '';
		foreach ($options as $option) {
			$output .= $this->renderOptionTag(
				$option['value'],
				$option['label'],
				$this->isSelectedAlternative($option)
			);
			$output .= chr(10);
		}
		return $output;
	}

	/**
	 * Check if option is selected
	 *
	 * @param array $option Current option
	 * @return boolean TRUE if the value should be marked a s selected; FALSE otherwise
	 */
	protected function isSelectedAlternative($option) {
		if (
			($option['selected'] && !$this->getValue()) || // preselect from flexform
			($this->getValue() && ($option['value'] == $this->getValue() || $option['label'] == $this->getValue())) // preselect from piVars
		) {
			return TRUE;
		}

		return FALSE;
	}
}