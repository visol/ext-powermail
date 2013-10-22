<?php
namespace In2code\Powermail\ViewHelpers\BeCheck;


/**
 * Backend Check Viewhelper: Check if TYPO3 Version is correct
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class T3VersionViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Check if TYPO3 Version is correct
	 *
	 * @return 	boolean
	 */
	public function render() {
		// settings
		global $EM_CONF, $_EXTKEY;
		$_EXTKEY = 'powermail';
		require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('powermail') . 'ext_emconf.php');
		$versionString = $EM_CONF[$_EXTKEY]['constraints']['depends']['typo3'];
		$versions = explode('-', $versionString);

		$isAboveMinVersion = (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) > \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger($versions[0]));
		$isBelowMaxVersion = (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) < \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger($versions[1]));
		if ($isAboveMinVersion && $isBelowMaxVersion) {
			return TRUE;
		}
		return FALSE;
	}
}