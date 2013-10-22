<?php
namespace In2code\Powermail\ViewHelpers\BeCheck;

/**
 * Backend Check Viewhelper: Check if Session works correct on the server
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class SessionViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Session Key
	 *
	 * @var string
	 */
	public $sessionKey = 'powermail_be_check_test';

	/**
	 * Check FE Session
	 *
	 * @return 	boolean
	 */
	public function render() {
		// settings
		global $TYPO3_CONF_VARS;
		$userObj = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
			'tslib_fe',
			$TYPO3_CONF_VARS,
			t3lib_div::_GET('id'),
			0,
			TRUE
		);
		$GLOBALS['TSFE']->fe_user = $userObj;

		// random value for session storing
		$value = md5(time());

		// store in session
		$GLOBALS['TSFE']->fe_user->setKey('ses', $this->sessionKey, $value);
		$GLOBALS['TSFE']->storeSessionData();

		if ($GLOBALS['TSFE']->fe_user->getKey('ses', $this->sessionKey) === $value) {
			return TRUE;
		}
		return FALSE;
	}
}