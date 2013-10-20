<?php

/**
 * Read Label of a field from given UID
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class Tx_Powermail_ViewHelpers_Getter_GetFieldLabelFromUidViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * fieldRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_FieldRepository
	 *
	 * @inject
	 */
	protected $fieldRepository;

	/**
	 * Read Label of a field from given UID
	 *
	 * @param 	int 		field uid
	 * @return 	string		Label
	 */
	public function render($uid) {
		$field = $this->fieldRepository->findByUid($uid);
		if (method_exists($field, 'getTitle')) {
			return $field->getTitle();
		}
	}
}