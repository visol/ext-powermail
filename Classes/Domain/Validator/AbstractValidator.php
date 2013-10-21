<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Thorsten Boock <thorsten@nerdcenter.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

abstract class Tx_Powermail_Domain_Validator_AbstractValidator extends Tx_Extbase_Validation_Validator_AbstractValidator {

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 *
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Return variable
	 *
	 * @var bool
	 */
	protected $isValid = TRUE;

	/**
	 * TypoScript Setup for powermail Pi1
	 */
	protected $settings;

	/**
	 * @param boolean $isValid
	 */
	public function setIsValid($isValid) {
		$this->isValid = $isValid;
	}

	/**
	 * @return boolean
	 */
	public function getIsValid() {
		return $this->isValid;
	}

	/**
	 * Set Error
	 *
	 * @param Tx_Powermail_Domain_Model_Field $field
	 * @param string $label
	 * @return void
	 */
	protected function setErrorAndMessage(Tx_Powermail_Domain_Model_Field $field, $label) {
		$this->setIsValid(FALSE);
		$this->addError($label, $field->getMarker());
	}

	/**
	 * @param Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager
	 * @return void
	 */
	public function injectTypoScript(Tx_Extbase_Configuration_ConfigurationManagerInterface $configurationManager) {
		$typoScriptSetup = $configurationManager->getConfiguration(
			Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->settings = $typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.'];
	}

}