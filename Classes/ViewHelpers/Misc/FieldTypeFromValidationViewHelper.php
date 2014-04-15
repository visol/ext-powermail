<?php
namespace In2code\Powermail\ViewHelpers\Misc;

use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Get Field Type for input fields
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class FieldTypeFromValidationViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * Configuration
	 */
	protected $settings;

	/**
	 * InputTypes
	 *
	 * @var array
	 */
	protected $html5InputTypes = array(
		1 => 'email',
		2 => 'url',
		4 => 'number',
		8 => 'range'
	);

	/**
	 * Parses variables again
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @return string
	 */
	public function render(\In2code\Powermail\Domain\Model\Field $field) {
		if (!$this->isHtml5ValidationEnabled()) {
			return 'text';
		}
		if (array_key_exists($field->getValidation(), $this->html5InputTypes)) {
			return $this->html5InputTypes[$field->getValidation()];
		}
		return 'text';
	}

	/**
	 * Checks if HTML5 validation was turned on by TypoScript
	 *
	 * @return bool
	 */
	protected function isHtml5ValidationEnabled() {
		return $this->settings['validation']['native'] === '1';
	}

	/**
	 * Injects the Configuration Manager
	 *
	 * @param ConfigurationManagerInterface $configurationManager
	 * @return void
	*/
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = \TYPO3\CMS\Core\Utility\GeneralUtility::removeDotsFromTS(
			$typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.']
		);
	}

}