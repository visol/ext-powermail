<?php
namespace In2code\Powermail\ViewHelpers\Getter;

/**
 * Get all field from form
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class GetFieldsFromFormViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Get all fields from form
	 *
	 * @param \In2code\Powermail\Domain\Model\Form $form
	 * @param string $property
	 * @return array
	 */
	public function render(\In2code\Powermail\Domain\Model\Form $form, $property = 'title') {
		$fields = array();
		foreach ($form->getPages() as $page) {
			foreach ($page->getFields() as $field) {
				$fields[] = $field->{'get' . ucfirst($property)}();
			}
		}
		return $fields;
	}

}