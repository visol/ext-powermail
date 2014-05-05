<?php
namespace In2code\Powermail\ViewHelpers\Validation;

/**
 * Array for multiple upload
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class MultipleUploadViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Array for multiple upload
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @param \array $additionalAttributes To add further attributes
	 * @return array
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field, $additionalAttributes = array()) {
		if ($field->getMultiselectForField()) {
			$additionalAttributes['multiple'] = 'multiple';
		}
		return $additionalAttributes;
	}
}