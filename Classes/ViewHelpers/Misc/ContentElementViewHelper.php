<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Shows Content Element
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class ContentElementViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $cObj;

	/**
	 * Parse a content element
	 *
	 * @param	int			UID of any content element
	 * @return 	string		Parsed Content Element
	 */
	public function render($uid) {
		$conf = array(
			'tables' => 'tt_content',
			'source' => $uid,
			'dontCheckPid' => 1
		);
		return $this->cObj->RECORDS($conf);
	}

	/**
	 * Injects the content object
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	*/
	public function injectContentObject(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->cObj = $configurationManager->getContentObject();
	}

}