<?php
namespace In2code\Powermail\Domain\Model;

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
class Answer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * value
	 *
	 * @var string
	 */
	protected $value = '';

	/**
	 * valueType
	 *
	 * @var int
	 */
	protected $valueType = 0;

	/**
	 * mail
	 *
	 * @var \In2code\Powermail\Domain\Model\Mail
	 */
	protected $mail = NULL;

	/**
	 * field
	 *
	 * @var \In2code\Powermail\Domain\Model\Field
	 */
	protected $field = NULL;

	/**
	 * Returns the value
	 *
	 * @return string $value
	 */
	public function getValue() {
		// workarround to get array from database (checkbox values)
		if (\In2code\Powermail\Utility\Div::is_serialized($this->value)) {
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
	 * @param int $valueType
	 */
	public function setValueType($valueType) {
		$this->valueType = $valueType;
	}

	/**
	 * @return int
	 */
	public function getValueType() {
		return $this->valueType;
	}

	/**
	 * Returns the mail
	 *
	 * @return \In2code\Powermail\Domain\Model\Mail $mail
	 */
	public function getMail() {
		return $this->mail;
	}

	/**
	 * Sets the mail
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	public function setMail(\In2code\Powermail\Domain\Model\Mail $mail) {
		$this->mail = $mail;
	}

	/**
	 * Returns the field
	 *
	 * @return \In2code\Powermail\Domain\Model\Field $field
	 */
	public function getField() {
		return $this->field;
	}

	/**
	 * Sets the field
	 *
	 * @param \In2code\Powermail\Domain\Model\Field $field
	 * @return void
	 */
	public function setField(\In2code\Powermail\Domain\Model\Field $field) {
		$this->field = $field;
	}

}