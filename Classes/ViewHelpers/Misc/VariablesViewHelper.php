<?php

/**
 * Parses Variables for powermail
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class Tx_Powermail_ViewHelpers_Misc_VariablesViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var Tx_Extbase_Configuration_ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 *
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Div Methods
	 *
	 * @var Tx_Powermail_Utility_Div
	 * @inject
	 */
	protected $div;

	/**
	 * Configuration
	 */
	protected $settings;

	/**
	 * Parses variables again
	 *
	 * @param array $variablesMarkers Variables and Markers array
	 * @param Tx_Powermail_Domain_Model_Mail $mail Variables and Labels array
	 * @param string $type "web" or "mail"
	 * @return string Changed string
	 */
	public function render($variablesMarkers = array(), Tx_Powermail_Domain_Model_Mail $mail, $type = 'web') {
		$parseObject = $this->objectManager->create('Tx_Fluid_View_StandaloneView');
		$parseObject->setTemplateSource($this->renderChildren());
		$parseObject->assignMultiple($this->div->htmlspecialcharsOnArray($variablesMarkers));

		$powermailAll = $this->div->powermailAll($mail, $this->configurationManager, $this->objectManager, $type, $this->settings);
		$parseObject->assign('powermail_all', $powermailAll);

		return html_entity_decode($parseObject->render());
	}

	/**
	 * Injects the Configuration Manager
	 *
	 * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
	 * @return void
	*/
	public function injectConfigurationManager(Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = t3lib_div::removeDotsFromTS($typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.']);
	}

}