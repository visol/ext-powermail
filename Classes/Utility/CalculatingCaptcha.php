<?php
namespace In2code\Powermail\Utility;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Div is a class for a collection of misc functions
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class CalculatingCaptcha {

	/**
	 * @var 	array		TypoScript
	 */
	protected $conf;

	/**
	 * Path to captcha image
	 *
	 * @var 	string		New Image Path
	 */
	public $captchaImage = 'EXT:powermail/Resources/Public/Image/captcha.png';

	/**
	 * Render Link to Captcha Image
	 */
	public function render($conf) {
		$this->conf = $conf;
		// get random string for captcha
		$string = $this->getString();
		// create image
		$content = $this->createImage($string);
		return $content;
	}

	/**
	 * Check if given code is correct
	 *
	 * @param string $code String to compare
	 * @param int $clearSession Flag if session should be cleared or not
	 * @return boolean
	 */
	public function validCode($code, $clearSession = 1) {
		$valid = 0;
		// if code is set and equal to session value
		if (intval($code) == $GLOBALS['TSFE']->fe_user->sesData['powermail_captcha_value'] && !empty($code)) {

			// clear session
			if ($clearSession) {
				$GLOBALS['TSFE']->fe_user->setKey('ses', 'powermail_captcha_value', '');
				$GLOBALS['TSFE']->storeSessionData();
			}

			// Set error code
			$valid = 1;
		}
		return $valid;
	}

	/**
	 * Create Image File
	 *
	 * @param $content
	 * @return string	Image HTML Code
	 */
	protected function createImage($content) {
		$subfolder = '';
		// if request_host is different to site_url (TYPO3 runs in a subfolder)
		if (GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' != GeneralUtility::getIndpEnv('TYPO3_SITE_URL')) {
			// get the folder (like "subfolder/")
			$subfolder = str_replace(
				GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/',
				'',
				GeneralUtility::getIndpEnv('TYPO3_SITE_URL')
			);
		}
		// background image
		$startimage = GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/' . $subfolder;
		$startimage .= $GLOBALS['TSFE']->tmpl->getFileName($this->conf['captcha.']['default.']['image']);

		// if file is correct
		if (!is_file($startimage)) {
			return 'Error: No Image found';
		}

		// Backgroundimage
		$img = ImageCreateFromPNG($startimage);
		$config = array();
		// change HEX color to RGB
		$config['color_rgb'] = sscanf($this->conf['captcha.']['default.']['textColor'], '#%2x%2x%2x');
		// Font color
		$config['color'] = ImageColorAllocate($img, $config['color_rgb'][0], $config['color_rgb'][1], $config['color_rgb'][2]);
		// fontfile
		$config['font'] = GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/' . $subfolder;
		$config['font'] .= $GLOBALS['TSFE']->tmpl->getFileName($this->conf['captcha.']['default.']['font']);
		// Fontsize
		$config['fontsize'] = $this->conf['captcha.']['default.']['textSize'];
		// give me the angles for the font
		$config['angle'] = GeneralUtility::trimExplode(',', $this->conf['captcha.']['default.']['textAngle'], 1);
		// random angle
		$config['fontangle'] = mt_rand($config['angle'][0], $config['angle'][1]);
		// give me the horizontal distances
		$config['distance_hor'] = GeneralUtility::trimExplode(',', $this->conf['captcha.']['default.']['distanceHor'], 1);
		// random distance
		$config['fontdistance_hor'] = mt_rand($config['distance_hor'][0], $config['distance_hor'][1]);
		// give me the vertical distances
		$config['distance_vert'] = GeneralUtility::trimExplode(',', $this->conf['captcha.']['default.']['distanceVer'], 1);
		// random distance
		$config['fontdistance_vert'] = mt_rand($config['distance_vert'][0], $config['distance_vert'][1]);
		// add text to image
		imagettftext(
			$img,
			$config['fontsize'],
			$config['fontangle'],
			$config['fontdistance_hor'],
			$config['fontdistance_vert'],
			$config['color'],
			$config['font'],
			$content
		);
		// save image file
		imagepng($img,
			GeneralUtility::getIndpEnv('TYPO3_DOCUMENT_ROOT') . '/' . $subfolder . $GLOBALS['TSFE']->tmpl->getFileName($this->captchaImage)
		);
		// delete temp image
		imagedestroy($img);

		// path to new image
		return $GLOBALS['TSFE']->tmpl->getFileName($this->captchaImage) . '?hash=' . time();
	}

	/**
	 * Create Random String for Captcha Image
	 *
	 * @return string
	 */
	protected function getString() {
		// config
		// 1. Get random numbers
		// operator +/-
		$op = mt_rand(0, 1);
		// loop max. 100 times
		for ($i = 0; $i < 100; $i++) {
			// random number 1
			$number1 = mt_rand(0, 15);
			// random number 2
			$number2 = mt_rand(0, 15);

			// don't want negative numbers
			if ($op != 1 || $number1 > $number2) {
				break;
			}
		}
		// give me the operator
		switch ($op) {
			case 0:
			default:
				// operator
				$operator = '+';
				// result
				$result = $number1 + $number2;
				break;

			case 1:
				$operator = '-';
				// result
				$result = $number1 - $number2;
				break;

			case 2:
				$operator = 'x';
				// result
				$result = $number1 * $number2;
				break;

			case 3:
				$operator = ':';
				// result
				$result = $number1 / $number2;
				break;
		}

		// Save result to session

		// Generate Session with result
		$GLOBALS['TSFE']->fe_user->setKey('ses', 'powermail_captcha_value', $result);
		// Save session
		$GLOBALS['TSFE']->storeSessionData();

		return $number1 . ' ' . $operator . ' ' . $number2;
	}

}