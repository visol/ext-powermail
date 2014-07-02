<?php
namespace In2code\Powermail\Utility;

use \TYPO3\CMS\Core\Utility\GeneralUtility,
	\In2code\Powermail\Domain\Model\Mail;

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
class Div {

	/**
	 * Extension Key
	 */
	public static $extKey = 'powermail';

	/**
	 * @var \In2code\Powermail\Domain\Repository\FormRepository
	 * @inject
	 */
	protected $formRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\FieldRepository
	 * @inject
	 */
	protected $fieldRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\MailRepository
	 * @inject
	 */
	protected $mailRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\UserRepository
	 * @inject
	 */
	protected $userRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Get Field Uid List from given Form Uid
	 *
	 * @param \integer $formUid Form Uid
	 * @return \array
	 */
	public function getFieldsFromForm($formUid) {
		$allowedFieldTypes = array(
			'input',
			'textarea',
			'select',
			'check',
			'radio',
			'password',
			'file',
			'hidden',
			'date',
			'location',
			'typoscript'
		);

		$fields = array();
		$form = $this->formRepository->findByUid($formUid);
		if (!method_exists($form, 'getPages')) {
			return array();
		}
		foreach ($form->getPages() as $page) {
			foreach ($page->getFields() as $field) {
				// skip type submit
				if (!in_array($field->getType(), $allowedFieldTypes)) {
					continue;
				}
				$fields[] = $field->getUid();
			}
		}

		return $fields;
	}

	/**
	 * Returns sendername from a couple of arguments
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail Given Params
	 * @return string Sender Name
	 */
	public function getSenderNameFromArguments(\In2code\Powermail\Domain\Model\Mail $mail) {
		$name = '';
		foreach ($mail->getAnswers() as $answer) {
			if (method_exists($answer->getField(), 'getUid') && $answer->getField()->getSenderName()) {
				$name .= $answer->getValue() . ' ';
			}
		}

		if (!$name) {
			$name = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_no_sender_name', 'powermail');
		}
		return trim($name);
	}

	/**
	 * Returns senderemail from a couple of arguments
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return string Sender Email
	 */
	public function getSenderMailFromArguments(\In2code\Powermail\Domain\Model\Mail $mail) {
		$email = '';
		foreach ($mail->getAnswers() as $answer) {
			if (
				method_exists($answer->getField(), 'getUid') &&
				$answer->getField()->getSenderEmail() &&
				GeneralUtility::validEmail($answer->getValue())
			) {
				$email = $answer->getValue();
				break;
			}
		}

		if (!$email) {
			$email = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_no_sender_email', 'powermail');
			$email .= '@';
			$email .= str_replace('www.', '', GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'));
		}
		return $email;
	}

	/**
	 * Save current timestamp to session
	 *
	 * @param int $formUid Form uid
	 * @return void
	 */
	public static function saveFormStartInSession($formUid) {
		if (intval($formUid) === 0) {
			return;
		}

		$GLOBALS['TSFE']->fe_user->setKey('ses', 'powermailFormstart' . $formUid, time());
		$GLOBALS['TSFE']->storeSessionData();
	}

	/**
	 * Read FormStart
	 *
	 * @param integer $formUid Form UID
	 * @return integer Timestamp
	 */
	public static function getFormStartFromSession($formUid) {
		$timestamp = $GLOBALS['TSFE']->fe_user->getKey('ses', 'powermailFormstart' . $formUid);
		return $timestamp;
	}

	/**
	 * Returns given number or the current PID
	 *
	 * @param integer $pid Storage PID or nothing
	 * @return integer $pid
	 */
	public static function getStoragePage($pid = 0) {
		if (!$pid) {
			$pid = $GLOBALS['TSFE']->id;
		}
		return $pid;
	}

	/**
	 * This functions renders the powermail_all Template (e.g. useage in Mails)
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param string $section Choose a section (web or mail)
	 * @param array $settings TypoScript Settings
	 * @return string content parsed from powermailAll HTML Template
	 */
	public function powermailAll(\In2code\Powermail\Domain\Model\Mail $mail, $section = 'web', $settings = array()) {
		$powermailAll = $this->objectManager->get('\TYPO3\CMS\Fluid\View\StandaloneView');
		$templatePathAndFilename = $this->getTemplatePath() . 'Form/PowermailAll.html';
		$powermailAll->setTemplatePathAndFilename($templatePathAndFilename);
		$powermailAll->setLayoutRootPath($this->getTemplatePath('layout'));
		$powermailAll->setPartialRootPath($this->getTemplatePath('partial'));
		$powermailAll->assign('mail', $mail);
		$powermailAll->assign('section', $section);
		$powermailAll->assign('settings', $settings);
		$content = $powermailAll->render();

		return $content;
	}

	/**
	 * Get absolute paths for templates with fallback
	 *
	 * @param string $part "template", "partial", "layout"
	 * @return string
	 */
	public function getTemplatePath($part = 'template') {
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
		);
		$templatePath = $extbaseFrameworkConfiguration['view'][$part . 'RootPath'];
		if (empty($templatePath)) {
			$templatePath = 'EXT:powermail/Resources/Private/' . ucfirst($part) . 's/';
		}
		$absoluteTemplatePath = GeneralUtility::getFileAbsFileName($templatePath);
		return $absoluteTemplatePath;
	}

	/**
	 * Generate a new array with markers and their values
	 * 		firstname => value
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return array new array
	 */
	public function getVariablesWithMarkers(\In2code\Powermail\Domain\Model\Mail $mail) {
		$variables = array();
		foreach ($mail->getAnswers() as $answer) {
			if (!method_exists($answer, 'getField') || !method_exists($answer->getField(), 'getMarker')) {
				continue;
			}
			$value = $answer->getValue();
			if (is_array($value)) {
				$value = implode(', ', $value);
			}
			$variables[$answer->getField()->getMarker()] = $value;
		}
		return $variables;
	}

	/**
	 * Generate a new array their labels and respect FE language
	 * 		Your Firstname: => value
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return array new array
	 */
	public function getVariablesWithLabels(\In2code\Powermail\Domain\Model\Mail $mail) {
		$variables = array();
		foreach ($mail->getAnswers() as $answer) {
			if (!method_exists($answer->getField(), 'getUid')) {
				continue;
			}
			$variables[] = array(
				'label' => $answer->getField()->getTitle(),
				'value' => $answer->getValue(),
				'uid' => $answer->getField()->getUid()
			);
		}
		return $variables;
	}

	/**
	 * Return marker from given field uid
	 *
	 * @param integer $uid Field UID
	 * @return string Marker name
	 */
	public function getMarkerFromField($uid) {
		// get field
		$field = $this->fieldRepository->findByUid($uid);

		$marker = NULL;
		if (method_exists($field, 'getMarker')) {
			$marker = $field->getMarker();
		}
		if ($marker === NULL || empty($marker)) {
			$marker = 'Error, could not get Marker';
		}

		return $marker;
	}

	/**
	 * Return uid from given field marker and form
	 *
	 * @param string $marker Field marker
	 * @param integer $formUid Form UID
	 * @return int Field UID
	 */
	public function getFieldUidFromMarker($marker, $formUid = 0) {
		$field = $this->fieldRepository->findByMarkerAndForm($marker, $formUid);
		if (method_exists($field, 'getUid')) {
			return $field->getUid();
		}
		return 0;
	}

	/**
	 * Return type from given field marker and form
	 *
	 * @param string $marker Field marker
	 * @param integer $formUid Form UID
	 * @return string Field Type
	 */
	public function getFieldTypeFromMarker($marker, $formUid = 0) {
		$field = $this->fieldRepository->findByMarkerAndForm($marker, $formUid);
		if (method_exists($field, 'getType')) {
			return $field->getType();
		}
		return '';
	}

	/**
	 * Return expected value type from fieldtype
	 *
	 * @param string $fieldType
	 * @return int
	 */
	public static function getDataTypeFromFieldType($fieldType) {
		$types = array(
			'captcha' => 0,
			'check' => 1,
			'content' => 0,
			'date' => 2,
			'file' => 3,
			'hidden' => 0,
			'html' => 0,
			'input' => 0,
			'location' => 0,
			'password' => 0,
			'radio' => 0,
			'reset' => 0,
			'select' => 1,
			'submit' => 0,
			'text' => 0,
			'textarea' => 0,
			'typoscript' => 0
		);
		if (array_key_exists($fieldType, $types)) {
			return $types[$fieldType];
		}
		return 0;
	}

	/**
	 * Overwrite a string if a TypoScript cObject is available
	 *
	 * @param string $string Value to overwrite
	 * @param array $conf TypoScript Configuration Array
	 * @param string $key Key for TypoScript Configuration
	 * @return void
	 */
	public function overwriteValueFromTypoScript(&$string, $conf, $key) {
		$cObj = $this->configurationManager->getContentObject();

		if ($cObj->cObjGetSingle($conf[$key], $conf[$key . '.'])) {
			$string = $cObj->cObjGetSingle($conf[$key], $conf[$key . '.']);
		}
	}

	/**
	 * Parse String with Fluid View
	 *
	 * @param string $string Any string
	 * @param array $variables Variables
	 * @return string Parsed string
	 */
	public function fluidParseString($string, $variables = array()) {
		$parseObject = $this->objectManager->get('\TYPO3\CMS\Fluid\View\StandaloneView');
		$parseObject->setTemplateSource($string);
		$parseObject->assignMultiple($variables);
		return $parseObject->render();
	}

	/**
	 * Use htmlspecialchars on array (key and value) (any depth - recursive call)
	 *
	 * @param array $array Any array
	 * @return array Cleaned array
	 */
	public function htmlspecialcharsOnArray($array) {
		$newArray = array();
		foreach ((array) $array as $key => $value) {
			if (is_array($value)) {
				$newArray[htmlspecialchars($key)] = $this->htmlspecialcharsOnArray($value);
			} else {
				$newArray[htmlspecialchars($key)] = htmlspecialchars($value);
			}
		}
		unset($array);
		return $newArray;
	}

	/**
	 * Get all receiver emails in an array
	 *
	 * @param string $receiverString String with some emails
	 * @param int $feGroup fe_groups Uid
	 * @return array
	 */
	public function getReceiverEmails($receiverString, $feGroup) {
		$array = $this->getEmailsFromString($receiverString);
		if ($feGroup) {
			$array = array_merge($array, $this->getEmailsFromFeGroup($feGroup));
		}
		return $array;
	}

	/**
	 * Read E-Mails from String
	 *
	 * @param int $uid fe_groups Uid
	 * @return array Array with emails
	 */
	public function getEmailsFromFeGroup($uid) {
		$users = $this->userRepository->findByUsergroup($uid);
		$array = array();
		foreach ($users as $user) {
			if (GeneralUtility::validEmail($user->getEmail())) {
				$array[] = $user->getEmail();
			}
		}
		return $array;
	}

	/**
	 * Read E-Mails from String
	 *
	 * @param string $string Any given string from a textarea with some emails
	 * @return array Array with emails
	 */
	public function getEmailsFromString($string) {
		$array = array();
		$string = str_replace(array("\n", '|', ','), ';', $string);
		$arr = GeneralUtility::trimExplode(';', $string, 1);
		foreach ($arr as $email) {
			$array[] = $email;
		}
		return $array;
	}

	/**
	 * Parse TypoScript from path like lib.blabla
	 *
	 * @param $typoScriptObjectPath
	 * @return string
	 */
	public static function parseTypoScriptFromTypoScriptPath($typoScriptObjectPath) {
		if (empty($typoScriptObjectPath)) {
			return '';
		}
		$setup = $GLOBALS['TSFE']->tmpl->setup;
		$contentObject = GeneralUtility::makeInstance('\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
		$pathSegments = GeneralUtility::trimExplode('.', $typoScriptObjectPath);
		$lastSegment = array_pop($pathSegments);
		foreach ($pathSegments as $segment) {
			$setup = $setup[$segment . '.'];
		}
		return $contentObject->cObjGetSingle($setup[$lastSegment], $setup[$lastSegment . '.']);
	}

	/**
	 * Create an options array (Needed for fieldsettings: select, radio, check)
	 * 		option1 =>
	 * 			label => Red Shoes
	 * 			value => red
	 * 			selected => 1
	 *
	 * @param string $string Options from the Textarea
	 * @param string $typoScriptObjectPath Path to TypoScript like lib.blabla
	 * @return array Options Array
	 */
	public static function optionArray($string, $typoScriptObjectPath) {
		if (empty($string)) {
			$string = self::parseTypoScriptFromTypoScriptPath($typoScriptObjectPath);
		}
		if (empty($string)) {
			$string = 'Error, no options to show';
		}
		$options = array();
		$string = str_replace('[\n]', "\n", $string);
		$settingsField = GeneralUtility::trimExplode("\n", $string, 1);
		foreach ($settingsField as $line) {
			$settings = GeneralUtility::trimExplode('|', $line, 0);
			$value = (isset($settings[1]) ? $settings[1] : $settings[0]);
			$options[] = array(
				'label' => $settings[0],
				'value' => $value,
				'selected' => isset($settings[2]) ? 1 : 0
			);
		}

		return $options;
	}

	/**
	 * Get grouped mail answers for reporting
	 *
	 * @param array $mails Mail array
	 * @param int $max Max Labels
	 * @param string $maxLabel Label for "Max Labels" - could be "all others"
	 * @return array
	 */
	public static function getGroupedMailAnswers($mails, $max = 5, $maxLabel = 'All others') {
		$arr = array();
		foreach ($mails as $mail) {
			foreach ($mail->getAnswers() as $answer) {
				$value = $answer->getValue();
				if (is_array($answer->getValue())) {
					$value = implode(', ', $value);
				}
				if (method_exists($answer->getField(), 'getUid')) {
					if (!isset($arr[$answer->getField()->getUid()][$value])) {
						$arr[$answer->getField()->getUid()][$value] = 1;
					} else {
						$arr[$answer->getField()->getUid()][$value]++;
					}
				}
			}
		}

		// sort desc
		foreach ($arr as $key => $value) {
			$value = NULL;

			arsort($arr[$key]);
		}

		// if too much values
		foreach ((array) $arr as $key => $array) {
			$array = NULL;

			if (count($arr[$key]) >= $max) {
				$i = 0;
				foreach ($arr[$key] as $value => $amount) {
					$i++;
					if ($i >= $max) {
						unset($arr[$key][$value]);
						if (!isset($arr[$key][$maxLabel])) {
							$arr[$key][$maxLabel] = $amount;
						} else {
							$arr[$key][$maxLabel] += $amount;
						}
					} else {
						$arr[$key][$value] = $amount;
					}
				}
			}
		}

		return $arr;
	}

	/**
	 * Get grouped marketing stuff for reporting
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mails Mails
	 * @param int $max Max Labels
	 * @param string $maxLabel Label for "Max Labels" - could be "all others"
	 * @return array
	 */
	public static function getGroupedMarketingStuff($mails, $max = 10, $maxLabel = 'All others') {
		$arr = array(
			'marketingRefererDomain' => array(),
			'marketingReferer' => array(),
			'marketingCountry' => array(),
			'marketingMobileDevice' => array(),
			'marketingFrontendLanguage' => array(),
			'marketingBrowserLanguage' => array(),
			'marketingPageFunnel' => array(),
		);
		foreach ($mails as $mail) {
			/** @var Mail $mail */
			foreach ($arr as $key => $v) {
				$v = NULL;

				$value = $mail->{'get' . ucfirst($key)}();
				if (is_array($value)) {
					$value = implode(',', $value);
				}
				if (!$value) {
					$value = '-';
				}
				if (!isset($arr[$key][$value])) {
					$arr[$key][$value] = 1;
				} else {
					$arr[$key][$value]++;
				}
			}
		}

		// sort desc
		foreach ($arr as $key => $value) {
			$value = NULL;

			arsort($arr[$key]);
		}

		// if too much values
		foreach ($arr as $key => $array) {
			$array = NULL;

			if (count($arr[$key]) >= $max) {
				$i = 0;
				foreach ($arr[$key] as $value => $amount) {
					$i++;
					if ($i >= $max) {
						unset($arr[$key][$value]);
						if (!isset($arr[$key][$maxLabel])) {
							$arr[$key][$maxLabel] = $amount;
						} else {
							$arr[$key][$maxLabel] += $amount;
						}
					} else {
						$arr[$key][$value] = $amount;
					}
				}
			}
		}

		return $arr;
	}

	/**
	 * Powermail SendPost - Send values via curl to a third party software
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param \array $conf TypoScript Configuration
	 * @return void
	 */
	public function sendPost($mail, $conf) {
		$contentObject = $this->configurationManager->getContentObject();

		// switch of if disabled
		$enable = $contentObject->cObjGetSingle(
			$conf['marketing.']['sendPost.']['_enable'],
			$conf['marketing.']['sendPost.']['_enable.']
		);
		if (!$enable) {
			return;
		}

		$contentObject->start(
			$this->getVariablesWithMarkers($mail)
		);
		$parameters = $contentObject->cObjGetSingle(
			$conf['marketing.']['sendPost.']['values'],
			$conf['marketing.']['sendPost.']['values.']
		);
		$curlSettings = array(
			'url' => $conf['marketing.']['sendPost.']['targetUrl'],
			'params' => $parameters
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curlSettings['url']);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlSettings['params']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($ch);
		curl_close($ch);

		// Debug Output
		if ($conf['marketing.']['sendPost.']['debug']) {
			GeneralUtility::devLog(
				'SendPost Values',
				'powermail',
				0,
				$curlSettings
			);
		}
	}

	/**
	 * Returns array with alphabetical letters
	 *
	 * @return array
	 */
	public static function getAbcArray() {
		$arr = array();
		for ($a = A; $a != AA; $a++) {
			$arr[] = $a;
		}
		return $arr;
	}

	/**
	 * Check of value is serialized
	 *
	 * @param string $val Any String
	 * @return bool
	 */
	public static function isSerialized($val) {
		if (!is_string($val) || trim($val) == '') {
			return FALSE;
		}
		if (preg_match('/^(i|s|a|o|d):(.*);/si', $val)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Check if String is JSON Array
	 *
	 * @param string $string
	 * @return bool
	 */
	public static function isJsonArray($string) {
		return is_array(json_decode($string));
	}

	/**
	 * Check if logged in user is allowed to make changes in Pi2
	 *
	 * @param array $settings $settings TypoScript and Flexform Settings
	 * @param int|\In2code\Powermail\Domain\Model\Mail $mail
	 * @return bool
	 */
	public function isAllowedToEdit($settings, $mail) {
		if (!is_a($mail, '\In2code\Powermail\Domain\Model\Mail')) {
			$mail = $this->mailRepository->findByUid(intval($mail));
		}
		if (!$GLOBALS['TSFE']->fe_user->user['uid']) {
			return FALSE;
		}

		// array with usergroups of current logged in user
		$usergroups = GeneralUtility::trimExplode(',', $GLOBALS['TSFE']->fe_user->user['usergroup'], 1);
		// array with all allowed users
		$usersSettings = GeneralUtility::trimExplode(',', $settings['edit']['feuser'], 1);
		// array with all allowed groups
		$usergroupsSettings = GeneralUtility::trimExplode(',', $settings['edit']['fegroup'], 1);

		// replace "_owner" with uid of owner in array with users
		if (method_exists($mail, 'getFeuser') && is_numeric(array_search('_owner', $usersSettings))) {
			$usersSettings[array_search('_owner', $usersSettings)] = $mail->getFeuser();
		}

		// add owner groups to allowed groups (if "_owner")
		// if one entry is "_ownergroup"
		if (method_exists($mail, 'getFeuser') && is_numeric(array_search('_owner', $usergroupsSettings))) {
			// get usergroups of owner user
			$usergroupsFromOwner = $this->getUserGroupsFromUser($mail->getFeuser());
			// add owner usergroups to allowed usergroups array
			$usergroupsSettings = array_merge((array) $usergroupsSettings, (array) $usergroupsFromOwner);
		}

		// 1. check user
		if (in_array($GLOBALS['TSFE']->fe_user->user['uid'], $usersSettings)) {
			return TRUE;
		}

		// 2. check usergroup
		// if there is one of the groups allowed
		if (count(array_intersect($usergroups, $usergroupsSettings))) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Check if
	 *
	 * @return bool
	 */
	public static function isBackendAdmin() {
		if (isset($GLOBALS['BE_USER']->user)) {
			return $GLOBALS['BE_USER']->user['admin'] === 1;
		}
		return FALSE;
	}

	/**
	 * Return usergroups uid of a given fe_user
	 *
	 * @param string $uid FE_user UID
	 * @return array Usergroups
	 */
	protected function getUserGroupsFromUser($uid) {
		$groups = array();
		$select = 'fe_groups.uid';
		$from = 'fe_users, fe_groups, sys_refindex';
		$where = 'sys_refindex.tablename = "fe_users"';
		$where .= ' AND sys_refindex.ref_table = "fe_groups"';
		$where .= ' AND fe_users.uid = sys_refindex.recuid AND fe_groups.uid = sys_refindex.ref_uid';
		$where .= ' AND fe_users.uid = ' . intval($uid);
		$groupBy = '';
		$orderBy = '';
		$limit = 1000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			// One loop for every entry
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$groups[] = $row['uid'];
			}
		}

		return $groups;
	}

	/**
	 * Check if given Hash is the correct Optin Hash
	 *
	 * @param \string $hash
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return \string
	 */
	public static function checkOptinHash($hash, Mail $mail) {
		$newHash = self::createHash($mail->getUid() . $mail->getPid() . $mail->getForm()->getUid());
		if ($newHash === $hash && !empty($hash)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Create Hash for Optin Mail
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return \string
	 */
	public static function createOptinHash(Mail $mail) {
		return self::createHash($mail->getUid() . $mail->getPid() . $mail->getForm()->getUid());
	}

	/**
	 * Create Hash from String and TYPO3 Encryption Key
	 *
	 * @param string $string Any String
	 * @return string Hashed String
	 */
	public static function createHash($string) {
		if (!empty($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'])) {
			$hash = GeneralUtility::shortMD5($string . $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
		} else {
			$hash = GeneralUtility::shortMD5($string);
		}
		return $hash;
	}

	/**
	 * Plain String output for given array
	 *
	 * @param array $array
	 * @return string
	 */
	public static function viewPlainArray($array) {
		$string = '';
		foreach ((array) $array as $key => $value) {
			$string .= $key . ': ' . $value . "\n";
		}
		return $string;
	}

	/**
	 * Store Marketing Information in Session
	 * 		'refererDomain' => domain.org
	 * 		'referer' => http://domain.org/xyz/test.html
	 * 		'country' => Germany
	 * 		'mobileDevice' => 1
	 * 		'frontendLanguage' => 3
	 * 		'browserLanguage' => en-us
	 * 		'feUser' => userAbc
	 * 		'pageFunnel' => array(2, 5, 1)
	 *
	 * @param \string $referer Referer
	 * @param \int $language Frontend Language Uid
	 * @param \int $pid Page Id
	 * @param \int $mobileDevice Is mobile device?
	 * @return void
	 */
	public static function storeMarketingInformation($referer = NULL, $language = 0, $pid = 0, $mobileDevice = 0) {
		$marketingInfo = self::getSessionValue('powermail_marketing');

		// initially create array with marketing info
		if (!is_array($marketingInfo)) {
			$marketingInfo = array(
				'refererDomain' => self::getDomainFromUri($referer),
				'referer' => $referer,
				'country' => self::getCountryFromIp(),
				'mobileDevice' => $mobileDevice,
				'frontendLanguage' => $language,
				'browserLanguage' => GeneralUtility::getIndpEnv('HTTP_ACCEPT_LANGUAGE'),
				'pageFunnel' => array($pid)
			);
		} else {
			// add current pid to funnel
			$marketingInfo['pageFunnel'][] = $pid;

			// clean pagefunnel if has more than 256 entries
			if (count($marketingInfo['pageFunnel']) > 256) {
				$marketingInfo['pageFunnel'] = array($pid);
			}
		}

		// store in session
		self::setSessionValue('powermail_marketing', $marketingInfo, TRUE);
	}

	/**
	 * Read MarketingInfos from Session
	 *
	 * @return array
	 */
	public static function getMarketingInfos() {
		$marketingInfo = self::getSessionValue('powermail_marketing');
		if (!is_array($marketingInfo)) {
			$marketingInfo = array(
				'refererDomain' => '',
				'referer' => '',
				'country' => '',
				'mobileDevice' => 0,
				'frontendLanguage' => 0,
				'browserLanguage' => '',
				'pageFunnel' => array()
			);
		}
		return $marketingInfo;
	}

	/**
	 * Get Property from currently logged in fe_user
	 *
	 * @param \string $propertyName
	 * @return \string
	 */
	public static function getPropertyFromLoggedInFeUser($propertyName = 'uid') {
		if (!empty($GLOBALS['TSFE']->fe_user->user[$propertyName])) {
			return $GLOBALS['TSFE']->fe_user->user[$propertyName];
		}
		return '';
	}

	/**
	 * Read domain from uri
	 *
	 * @param \string $uri
	 * @return \string
	 */
	public static function getDomainFromUri($uri) {
		$uriParts = parse_url($uri);
		return $uriParts['host'];
	}

	/**
	 * Get Country Name out of an IP address
	 *
	 * @param \string $ip
	 * @return \string Countryname
	 */
	public static function getCountryFromIp($ip = NULL) {
		if ($ip === NULL) {
			$ip = GeneralUtility::getIndpEnv('REMOTE_ADDR');
		}
		$json = GeneralUtility::getUrl('http://freegeoip.net/json/' . $ip);
		$geoInfo = json_decode($json);
		if (!empty($geoInfo->country_name)) {
			return $geoInfo->country_name;
		}
		return '';
	}

	/**
	 * Set a powermail session (don't overwrite existing sessions)
	 *
	 * @param string $name A session name
	 * @param array $values Values to save
	 * @param \bool $overwrite Overwrite existing values
	 * @return void
	 */
	public static function setSessionValue($name, $values, $overwrite = FALSE) {
		if (!$overwrite) {
			// read existing values
			$oldValues = self::getSessionValue($name);
			// merge old values with new
			$values = array_merge((array) $oldValues, (array) $values);
		}
		$newValues = array(
			$name => $values
		);

		$GLOBALS['TSFE']->fe_user->setKey('ses', self::$extKey, $newValues);
		$GLOBALS['TSFE']->storeSessionData();
	}

	/**
	 * Read a powermail session
	 *
	 * @param \string $name A session name
	 * @return \string Values from session
	 */
	public static function getSessionValue($name = '') {
		$powermailSession = $GLOBALS['TSFE']->fe_user->getKey('ses', self::$extKey);
		if (!empty($name) && isset($powermailSession[$name])) {
			return $powermailSession[$name];
		}
		return '';
	}

	/**
	 * Save values to any table in TYPO3 database
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param \array $conf TypoScript Configuration
	 * @return void
	 */
	public function saveToAnyTable($mail, $conf) {
		if (empty($conf['dbEntry.'])) {
			return;
		}
		$contentObject = $this->configurationManager->getContentObject();
		$startArray = $this->getVariablesWithMarkers($mail);

		// one loop per table
		foreach ((array) $conf['dbEntry.'] as $table => $settings) {
			$settings = NULL;

			// remove ending .
			$table = substr($table, 0, -1);

			// skip this table if disabled
			$enable = $contentObject->cObjGetSingle(
				$conf['dbEntry.'][$table . '.']['_enable'],
				$conf['dbEntry.'][$table . '.']['_enable.']
			);
			if (!$enable) {
				continue;
			}

			/* @var $storeObject \In2code\Powermail\Utility\SaveToAnyTable */
			$storeObject = $this->objectManager->get('In2code\Powermail\Utility\SaveToAnyTable');
			$storeObject->setTable($table);
			$contentObject->start($startArray);

			// if unique was set
			if (!empty($conf['dbEntry.'][$table . '.']['_ifUnique.'])) {
				$uniqueFields = array_keys($conf['dbEntry.'][$table . '.']['_ifUnique.']);
				$storeObject->setMode($conf['dbEntry.'][$table . '.']['_ifUnique.'][$uniqueFields[0]]);
				$storeObject->setUniqueField($uniqueFields[0]);
			}

			// one loop per field
			foreach ((array) $conf['dbEntry.'][$table . '.'] as $field => $settingsInner) {
				$settingsInner = NULL;

				// skip if key. or if it starts with _
				if (stristr($field, '.') || $field[0] === '_') {
					continue;
				}

				// read from TypoScript
				$value = $contentObject->cObjGetSingle(
					$conf['dbEntry.'][$table . '.'][$field],
					$conf['dbEntry.'][$table . '.'][$field . '.']
				);
				$storeObject->addProperty($field, $value);
			}
			if (!empty($conf['debug.']['saveToTable'])) {
				$storeObject->setDevLog(TRUE);
			}
			$uid = $storeObject->execute();

			// add this uid to startArray for using in TypoScript
			$startArray = array_merge(
				$startArray,
				array('uid_' . $table => $uid)
			);
		}
	}

	/**
	 * Read pid from current URL
	 * 		URL example:
	 * 		http://powermailt361.in2code.de/typo3/alt_doc.php?
	 * 		&returnUrl=%2Ftypo3%2Fsysext%2Fcms%2Flayout%2Fdb_layout.php%3Fid%3D17%23
	 * 		element-tt_content-14&edit[tt_content][14]=edit
	 *
	 * @return int
	 */
	public static function getPidFromBackendPage() {
		$pid = 0;
		$backUrl = str_replace('?', '&', GeneralUtility::_GP('returnUrl'));
		$urlParts = GeneralUtility::trimExplode('&', $backUrl, 1);
		foreach ($urlParts as $part) {
			if (stristr($part, 'id=')) {
				$pid = str_replace('id=', '', $part);
			}
		}

		return intval($pid);
	}

	/**
	 * Get Subfolder of current TYPO3 Installation
	 *
	 * @param bool $leadingSlash
	 * @param bool $trailingSlash
	 * @return string
	 */
	public static function getSubFolderOfCurrentUrl($leadingSlash = TRUE, $trailingSlash = FALSE) {
		$subfolder = '';
		if (GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/' !== GeneralUtility::getIndpEnv('TYPO3_SITE_URL')) {
			$subfolder = str_replace(
				GeneralUtility::getIndpEnv('TYPO3_REQUEST_HOST') . '/',
				'',
				GeneralUtility::getIndpEnv('TYPO3_SITE_URL')
			);
			if ($leadingSlash) {
				$subfolder = '/' . $subfolder;
			}
			if (!$trailingSlash) {
				$subfolder = substr($subfolder, 0, -1);
			}
		}
		return $subfolder;
	}

	/**
	 * Get Fieldlist from Form UID
	 *
	 * @param int $formUid Form UID
	 * @return array
	 */
	public static function getFieldsFromFormWithSelectQuery($formUid) {
		$select = '
			tx_powermail_domain_model_fields.uid,
			tx_powermail_domain_model_fields.title,
			tx_powermail_domain_model_fields.sender_email,
			tx_powermail_domain_model_fields.sender_name
		';
		$select .= ', tx_powermail_domain_model_fields.marker';
		$from = '
			tx_powermail_domain_model_fields
			left join tx_powermail_domain_model_pages on tx_powermail_domain_model_fields.pages = tx_powermail_domain_model_pages.uid
			left join tx_powermail_domain_model_forms on tx_powermail_domain_model_pages.forms = tx_powermail_domain_model_forms.uid
		';
		$where = '
			tx_powermail_domain_model_fields.deleted = 0 and
			tx_powermail_domain_model_fields.hidden = 0 and
			tx_powermail_domain_model_fields.type != "submit" and
			tx_powermail_domain_model_fields.sys_language_uid IN (-1,0) and
			tx_powermail_domain_model_forms.uid = ' . intval($formUid);
		$groupBy = '';
		$orderBy = 'tx_powermail_domain_model_fields.sorting ASC';
		$limit = 10000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);

		$array = array();
		if ($res) {
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) {
				$array[] = $row;
			}
		}

		return $array;
	}

	/**
	 * Merges Flexform, TypoScript and Extension Manager Settings (up to 2 levels)
	 * 		Note: It's not possible to have the same field in TypoScript and Flexform
	 * 		and if FF value is empty, we want the TypoScript value instead
	 *
	 * @param array $settings All settings
	 * @param string $typoScriptLevel Startpoint
	 * @return void
	 */
	public static function mergeTypoScript2FlexForm(&$settings, $typoScriptLevel = 'setup') {
		// config
		$temporarySettings = array();
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['powermail']);

		if (isset($settings[$typoScriptLevel]) && is_array($settings[$typoScriptLevel])) {
			// copy typoscript part to conf part
			$temporarySettings = $settings[$typoScriptLevel];
		}

		if (isset($settings['flexform']) && is_array($settings['flexform'])) {
			// copy flexform part to conf part
			$temporarySettings = array_merge((array) $temporarySettings, (array) $settings['flexform']);
		}

		// merge ts and ff (loop every flexform)
		foreach ($temporarySettings as $key1 => $value1) {
			// 1. level
			if (!is_array($value1)) {
				// only if this key exists in ff and ts
				if (isset($settings[$typoScriptLevel][$key1]) && isset($settings['flexform'][$key1])) {
					// only if ff is empty and ts not
					if ($settings[$typoScriptLevel][$key1] && !$settings['flexform'][$key1]) {
						// overwrite with typoscript settings
						$temporarySettings[$key1] = $settings[$typoScriptLevel][$key1];
					}
				}
			} else {
				// 2. level
				foreach ($value1 as $key2 => $value2) {
					$value2 = NULL;

					// only if this key exists in ff and ts
					if (isset($settings[$typoScriptLevel][$key1][$key2]) && isset($settings['flexform'][$key1][$key2])) {
						// only if ff is empty and ts not
						if ($settings[$typoScriptLevel][$key1][$key2] && !$settings['flexform'][$key1][$key2]) {
							// overwrite with typoscript settings
							$temporarySettings[$key1][$key2] = $settings[$typoScriptLevel][$key1][$key2];
						}
					}
				}
			}
		}

		// merge ts and ff (loop every typoscript)
		foreach ((array) $settings[$typoScriptLevel] as $key1 => $value1) {
			// 1. level
			if (!is_array($value1)) {
				// only if this key exists in ts and not in ff
				if (isset($settings[$typoScriptLevel][$key1]) && !isset($settings['flexform'][$key1])) {
					// set value from ts
					$temporarySettings[$key1] = $value1;
				}
			} else {
				// 2. level
				foreach ($value1 as $key2 => $value2) {
					// only if this key exists in ts and not in ff
					if (isset($settings[$typoScriptLevel][$key1][$key2]) && !isset($settings['flexform'][$key1][$key2])) {
						// set value from ts
						$temporarySettings[$key1][$key2] = $value2;
					}
				}
			}
		}

		// add global config
		$temporarySettings['global'] = $confArr;

		$settings = $temporarySettings;
		unset($temporarySettings);
	}

}