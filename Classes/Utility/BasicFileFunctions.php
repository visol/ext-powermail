<?php
namespace In2code\Powermail\Utility;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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
 * Basic File Functions
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class BasicFileFunctions {

	/**
	 * Return Unique Filename for File Upload
	 *
	 * @param array $files
	 * @param string $destinationPath
	 * @param bool $addPath
	 * @return array
	 */
	public static function getUniqueNamesForFileUploads($files, $destinationPath, $addPath = TRUE) {
		// stop unique function if there is no $files[0]['tmp_name']
		if (!is_array($files[0])) {
			return $files;
		}

		$newFileNames = array();
		foreach ((array) $files as $file) {
			if (!empty($file['name'])) {
				$newFileName = self::getUniqueName($file['name'], $destinationPath, $addPath);
				$newFileNames[] = $newFileName;
			}
		}
		return $newFileNames;
	}

	/**
	 * Get Uniquename out of a relative path
	 *
	 * @param string $filename
	 * @param string $destinationPath Relative Path to destination
	 * @param bool $addPath
	 * @return string
	 */
	public static function getUniqueName($filename, $destinationPath, $addPath = TRUE) {
		self::cleanFileName($filename);

		/** @var \TYPO3\CMS\Core\Utility\File\BasicFileUtility $basicFileFunctions */
		$basicFileFunctions = GeneralUtility::makeInstance('\TYPO3\CMS\Core\Utility\File\BasicFileUtility');
		$newFileName = $basicFileFunctions->getUniqueName(
			$filename,
			GeneralUtility::getFileAbsFileName($destinationPath)
		);
		if (!$addPath) {
			$newFileName = basename($newFileName);
		}
		return $newFileName;
	}

	/**
	 * Only allowed a-z, A-Z, 0-9, -, .
	 * Others will be replaced
	 *
	 * @param string $filename
	 * @param string $replace
	 * @return void
	 */
	public static function cleanFileName(&$filename, $replace = '_') {
		$filename = preg_replace('/[^a-zA-Z0-9-\.]/', $replace, trim($filename));
	}

	/**
	 * Get File Upload Values from Unique Name (for File Uploads)
	 * 		array(
	 * 			picture_03.jpg => array(tmp_name => tmp\xazab23, name => picture.jpg)
	 * 			text_01.jpg => array(tmp_name => tmp\89706fa, name => text.jpg)
	 * 		)
	 *
	 * @param string $destinationPath
	 * @return array
	 */
	public static function getFileUploadValuesOutOfUniqueName($destinationPath) {
		$fileArray = array();
		if (isset($_FILES['tx_powermail_pi1']['name']['field'])) {
			foreach (array_keys($_FILES['tx_powermail_pi1']['name']['field']) as $marker) {
				foreach ($_FILES['tx_powermail_pi1']['name']['field'][$marker] as $key => $originalFileName) {
					// switch from original to unique
					$fileArray[self::getUniqueName($originalFileName, $destinationPath, FALSE)] = array(
						'name' => $_FILES['tx_powermail_pi1']['name']['field'][$marker][$key],
						'type' => $_FILES['tx_powermail_pi1']['name']['type'][$marker][$key],
						'tmp_name' => $_FILES['tx_powermail_pi1']['tmp_name']['field'][$marker][$key],
						'error' => $_FILES['tx_powermail_pi1']['error']['field'][$marker][$key],
						'size' => $_FILES['tx_powermail_pi1']['size']['field'][$marker][$key]
					);
				}
			}
		}
		return $fileArray;
	}

	/**
	 * File Upload
	 *
	 * @param string $destinationPath
	 * @param string $allowedFileExtensions
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public static function fileUpload($destinationPath, $allowedFileExtensions = '', \In2code\Powermail\Domain\Model\Mail $mail) {
		$result = FALSE;
		if (isset($_FILES['tx_powermail_pi1']['tmp_name']['field']) && self::hasFormAnUploadField($mail->getForm())) {
			foreach (array_keys($_FILES['tx_powermail_pi1']['tmp_name']['field']) as $marker) {
				foreach ($_FILES['tx_powermail_pi1']['tmp_name']['field'][$marker] as $key => $tmpName) {
					$uniqueFileName = self::getUniqueName($_FILES['tx_powermail_pi1']['name']['field'][$marker][$key], $destinationPath);
					if (!self::checkExtension($uniqueFileName, $allowedFileExtensions)) {
						continue;
					}
					$result = GeneralUtility::upload_copy_move($tmpName, $uniqueFileName);
				}
			}
		}
		return $result;
	}

	/**
	 * Is file-extension allowed for uploading?
	 *
	 * @param string $filename Filename like (upload_03.txt)
	 * @param string $allowedFileExtensions
	 * @return bool
	 */
	public static function checkExtension($filename, $allowedFileExtensions = '') {
		$fileInfo = pathinfo($filename);
		if (
			!empty($fileInfo['extension']) &&
			!empty($allowedFileExtensions) &&
			GeneralUtility::inList($allowedFileExtensions, $fileInfo['extension']) &&
			GeneralUtility::verifyFilenameAgainstDenyPattern($filename)
		) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Is file size ok?
	 *
	 * @param string $filename Filename like (upload_03.txt)
	 * @param array $settings
	 * @return bool
	 */
	public static function checkFilesize($filename, $settings) {
		$fileUploads = self::getFileUploadValuesOutOfUniqueName($settings['misc.']['file.']['folder']);
		if (filesize($fileUploads[$filename]['tmp_name']) <= $settings['misc.']['file.']['size']) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Get all Files from a folder
	 *
	 * @param string $path Relative Path
	 * @return array
	 */
	public static function getFilesFromRelativePath($path) {
		$array = array();
		$files = GeneralUtility::getFilesInDir(
			GeneralUtility::getFileAbsFileName($path)
		);
		foreach ($files as $file) {
			$array[] = $file;
		}
		return $array;
	}

	/**
	 * Check if this form has an upload field
	 *
	 * @param \In2code\Powermail\Domain\Model\Form $form
	 * @return bool
	 */
	public static function hasFormAnUploadField(\In2code\Powermail\Domain\Model\Form $form) {
		foreach ($form->getPages() as $page) {
			foreach ($page->getFields() as $field) {
				if ($field->getType() === 'file') {
					return TRUE;
				}
			}
		}
		return FALSE;
	}
}