<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Get Captcha
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class CaptchaViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Configuration
	 */
	protected $settings;

	/**
	 * Returns Captcha-Image String
	 *
	 * @return string HTML-Tag for Captcha image
	 */
	public function render() {
		$captcha = t3lib_div::makeInstance('\In2code\Powermail\Utility\CalculatingCaptcha');
		return $captcha->render($this->settings);
	}

	/**
	 * Object initialization
	 */
	public function initializeObject() {
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterfac::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = $typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.'];
	}
}