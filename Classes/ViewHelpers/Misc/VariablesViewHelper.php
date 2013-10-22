<?php
namespace In2code\Powermail\ViewHelpers\Misc;

/**
 * Parses Variables for powermail
 *
 * @package TYPO3
 * @subpackage Fluid
 */
class VariablesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 *
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Div Methods
	 *
	 * @var \In2code\Powermail\Utility\Div
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
	 * @param \In2code\Powermail\Domain\Model\Mail $mail Variables and Labels array
	 * @param string $type "web" or "mail"
	 * @return string Changed string
	 */
	public function render($variablesMarkers = array(), \In2code\Powermail\Domain\Model\Mail $mail, $type = 'web') {
		$parseObject = $this->objectManager->get('\TYPO3\CMS\Fluid\View\StandaloneView');
		$parseObject->setTemplateSource($this->renderChildren());
		$parseObject->assignMultiple($this->div->htmlspecialcharsOnArray($variablesMarkers));

		$powermailAll = $this->div->powermailAll($mail, $type, $this->settings);
		$parseObject->assign('powermail_all', $powermailAll);

		return html_entity_decode($parseObject->render());
	}

	/**
	 * Injects the Configuration Manager
	 *
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 * @return void
	*/
	public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
		$this->configurationManager = $configurationManager;
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = \TYPO3\CMS\Core\Utility\GeneralUtility::removeDotsFromTS(
			$typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.']
		);
	}

}