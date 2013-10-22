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
 * Class for uploading files and check if they are valid
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Tx_Powermail_Domain_Validator_UploadValidator extends Tx_Powermail_Domain_Validator_AbstractValidator {

	/**
	 * BasicFileFunctions
	 */
	public $basicFileFunctions;

	/**
	 * Validation of given Mail Params
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isValid($mail) {
		if (isset($_FILES['tx_powermail_pi1']['name']['field'])) {
			// session stuff
			$uploadSession = array();
			Tx_Powermail_Utility_Div::setSessionValue('upload', array(), TRUE); // clean old session before

			foreach ($mail->getAnswers() as $answer) {
				if ($answer->getField()->getType() != 'upload') {
					continue;
				}

				$fileName = $_FILES['tx_powermail_pi1']['name']['field'][$answer->getField()->getMarker()];
				$tmpName = $_FILES['tx_powermail_pi1']['tmp_name']['field'][$answer->getField()->getMarker()];

				// if no file given
				if (!$answer->getValue() || empty($fileName)) {
					continue;
				}

				// Check extension
				if (!$this->checkExtension($fileName, $answer->getField())) {
					continue;
				}

				// Check filesize
				if (!$this->checkFilesize($tmpName, $answer->getField())) {
					continue;
				}

				// create new filename with absolute path
				$newFile = $this->basicFileFunctions->getUniqueName(
					$fileName,
					t3lib_div::getFileAbsFileName($this->settings['misc.']['file.']['folder'])
				);
				$uploadSession[] = $newFile; // create array for upload session
				if (!t3lib_div::upload_copy_move($tmpName, $newFile)) {
					$this->setErrorAndMessage($answer->getField(), 'upload_error');
				}
			}

			// save uploaded filenames to session (to attach it later)
			Tx_Powermail_Utility_Div::setSessionValue('upload', $uploadSession, TRUE);
		}

		return $this->getIsValid();
	}

	/**
	 * Is file-extension allowed for uploading?
	 *
	 * @param string $filename Filename like (upload.txt)
	 * @param Tx_Powermail_Domain_Model_Field $field
	 * @return bool
	 */
	protected function checkExtension($filename, Tx_Powermail_Domain_Model_Field $field) {
		$fileInfo = pathinfo($filename);
		if (!isset($fileInfo['extension']) || !t3lib_div::inList($this->settings['misc.']['file.']['extension'], $fileInfo['extension'])) {
			$this->setErrorAndMessage($field, 'upload_extension');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Is filesize small enough?
	 *
	 * @param string $tmpName
	 * @param Tx_Powermail_Domain_Model_Field $field
	 * @return bool
	 */
	protected function checkFilesize($tmpName, Tx_Powermail_Domain_Model_Field $field) {
		if (filesize($tmpName) > $this->settings['misc.']['file.']['size']) {
			$this->setErrorAndMessage($field, 'upload_size');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->basicFileFunctions = t3lib_div::makeInstance('t3lib_basicFileFunctions');
	}
}