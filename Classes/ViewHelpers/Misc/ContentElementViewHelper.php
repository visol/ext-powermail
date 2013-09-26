<?php

/**
 * Shows Content Element
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class Tx_Powermail_ViewHelpers_Misc_ContentElementViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var tslib_cObj
	 */
	protected $cObj;

    /**
     * Parse a content element
     *
	 * @param	int			UID of any content element
     * @return 	string		Parsed Content Element
     */
    public function render($uid) {
		$conf = array( // config
			'tables' => 'tt_content',
			'source' => $uid,
			'dontCheckPid' => 1
		);
		return $this->cObj->RECORDS($conf);
    }

	/**
	 * Injects the content object
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
	 * @return void
	*/
	public function injectContentObject(Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
		$this->cObj = $configurationManager->getContentObject();
	}

}

?>