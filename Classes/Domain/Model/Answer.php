<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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


/**
 * Answer Model
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_Powermail_Domain_Model_Answer extends Tx_Extbase_DomainObject_AbstractEntity {

	/**
	 * value
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * mail
	 *
	 * @var Tx_Powermail_Domain_Model_Mail
	 */
	protected $mail = NULL;

	/**
	 * field
	 *
	 * @var Tx_Powermail_Domain_Model_Field
	 */
	protected $field = NULL;

	/**
	 * Returns the value
	 *
	 * @return string $value
	 */
	public function getValue() {
		// workarround to get array from database (checkbox values)
		if (Tx_Powermail_Utility_Div::is_serialized($this->value)) {
			return unserialize($this->value);
		}
		return $this->value;
	}

	/**
	 * Sets the value
	 *
	 * @param string $value
	 * @dontvalidate $value
	 * @return void
	 */
	public function setValue($value) {
		// workarround to store array in database (checkbox values)
		if (is_array($value)) {
			$value = serialize($value);
		}
		$this->value = $value;
	}

	/**
	 * Returns the mail
	 *
	 * @return Tx_Powermail_Domain_Model_Mail $mail
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * Sets the mail
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	public function setMail(Tx_Powermail_Domain_Model_Mail $mail) {
		$this->mail = $mail;
	}

	/**
	 * Returns the field
	 *
	 * @return Tx_Powermail_Domain_Model_Field $field
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Sets the field
	 *
	 * @param Tx_Powermail_Domain_Model_Field $field
	 * @return void
	 */
	public function setField(Tx_Powermail_Domain_Model_Field $field) {
		$this->field = $field;
	}

}