<?php

/**
 * View helper check if given value is array or not
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class Tx_Powermail_ViewHelpers_Getter_GetPageNameFromUidViewHelper extends Tx_Fluid_ViewHelpers_Form_AbstractFormFieldViewHelper {

	/**
	 * pageRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_PageRepository
	 */
	protected $pageRepository;

    /**
     * View helper check if given value is array or not
     *
     * @param 	int 		PID
     * @return 	string		Page Name
     */
    public function render($uid = '') {
		return $this->pageRepository->getPageNameFromUid($uid);
    }

	/**
	 * injectPageRepository
	 *
	 * @param Tx_Powermail_Domain_Repository_PageRepository $pageRepository
	 * @return void
	 */
	public function injectPageRepository(Tx_Powermail_Domain_Repository_PageRepository $pageRepository) {
		$this->pageRepository = $pageRepository;
	}
}

?>