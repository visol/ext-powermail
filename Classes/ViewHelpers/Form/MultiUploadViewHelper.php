<?php
namespace In2code\Powermail\ViewHelpers\Form;

/**
 * View helper to get a country array
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class MultiUploadViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\Form\UploadViewHelper {

	/**
	 * Initialize the arguments.
	 *
	 * @return void
	 * @api
	 */
	public function initializeArguments() {
		parent::initializeArguments();
	}

	/**
	 * Renders the upload field.
	 *
	 * @return string
	 */
	public function render() {
		$name = $this->getName();
		$allowedFields = array('name', 'type', 'tmp_name', 'error', 'size');
		foreach ($allowedFields as $fieldName) {
			if ($this->isMultiple()) {
				$this->registerFieldNameForFormTokenGeneration($name . '[' . $fieldName . '][]');
			} else {
				$this->registerFieldNameForFormTokenGeneration($name . '[' . $fieldName . ']');
			}
		}
		$this->tag->addAttribute('type', 'file');
		if ($this->isMultiple()) {
			$name .= '[]';
		}
		$this->tag->addAttribute('name', $name);
		$this->setErrorClassAttribute();
		return $this->tag->render();
	}

	/**
	 * Check if upload is set to multiple
	 *
	 * @return bool
	 */
	protected function isMultiple() {
		if (
			(isset($this->arguments['multiple']) && $this->arguments['multiple'] === 'multiple')
			|| (
				isset($this->arguments['additionalAttributes']['multiple']) &&
				$this->arguments['additionalAttributes']['multiple'] === 'multiple'
			)
		) {
			return TRUE;
		}
		return FALSE;
	}
}