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
	 * @return bool
	 */
	public static function fileUpload($destinationPath) {
		$result = FALSE;
		if (isset($_FILES['tx_powermail_pi1']['tmp_name']['field'])) {
			foreach (array_keys($_FILES['tx_powermail_pi1']['tmp_name']['field']) as $marker) {
				foreach ($_FILES['tx_powermail_pi1']['tmp_name']['field'][$marker] as $key => $tmpName) {
					$result = GeneralUtility::upload_copy_move(
						$tmpName,
						self::getUniqueName($_FILES['tx_powermail_pi1']['name']['field'][$marker][$key], $destinationPath)
					);
				}
			}
		}
		return $result;
	}
}