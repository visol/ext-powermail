<?php
namespace In2code\Powermail\ViewHelpers\Getter;

/**
 * Read Label of a field from given UID
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class GetFieldLabelFromUidViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * fieldRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\FieldRepository
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
			$result = $field->getTitle();
		}

		return $result;
	}
}