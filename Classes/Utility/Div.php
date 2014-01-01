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
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Div {

	/**
	 * Extension Key
	 */
	public static $extKey = 'powermail';

	/**
	 * @var \In2code\Powermail\Domain\Repository\FormRepository
	 *
	 * @inject
	 */
	protected $formRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\FieldRepository
	 *
	 * @inject
	 */
	protected $fieldRepository;

	/**
	 * @var \In2code\Powermail\Domain\Repository\UserRepository
	 *
	 * @inject
	 */
	protected $userRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 *
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 *
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Get Field Uid List from given Form Uid
	 *
	 * @param integer $formUid Form Uid
	 * @return array
	 */
	public function getFieldsFromForm($formUid) {
		$fields = array();
		$form = $this->formRepository->findByUid($formUid);
		if (!method_exists($form, 'getPages')) {
			return;
		}
		foreach ($form->getPages() as $page) {
			foreach ($page->getFields() as $field) {
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
			if (method_exists($answer->getField(), 'getUid') && $answer->getField()->getSenderEmail() && GeneralUtility::validEmail($answer->getValue())) {
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
	 * @param integer $formUid Form uid
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
	 * This functions renders the powermail_all Template to use in Mails and Other views
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param string $section Choose a section (web or mail)
	 * @param array $settings TypoScript Settings
	 * @return string content parsed from powermailAll HTML Template
	 */
	public function powermailAll(\In2code\Powermail\Domain\Model\Mail $mail, $section = 'web', $settings = array()) {
		$powermailAll = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
		);
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']) . 'Form/PowermailAll.html';
		$powermailAll->setLayoutRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['layoutRootPath']));
		$powermailAll->setPartialRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['partialRootPath']));
		$powermailAll->setTemplatePathAndFilename($templatePathAndFilename);
		$powermailAll->assign('mail', $mail);
		$powermailAll->assign('section', $section);
		$powermailAll->assign('settings', $settings);
		$content = $powermailAll->render();

		return $content;
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
		$field = $this->fieldRepository->findByUid($uid); // get field
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
			'select' => 0,
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
	 * Add uploads fields and rewrite date fields
	 *
	 * @param array $fields Field array
	 * @return void
	 */
	public function rewriteDateInFields($fields) {
		// rewrite datetime
		foreach ((array) $fields as $uid => $value) {
			$field = $this->fieldRepository->findByUid($uid);
			if (method_exists($field, 'getType') && $field->getType() == 'date') {
				$fields[$uid] = strtotime($value);
			}
		}
		return $fields;
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
		$parseObject = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
		$parseObject->setTemplateSource($string);
		$parseObject->assignMultiple($variables);
		return $parseObject->render();
	}

	/**
	 * Function makePlain() removes html tags and add linebreaks
	 * 		Easy generate a plain email bodytext from a html bodytext
	 *
	 * @param string $content: HTML Mail bodytext
	 * @return string $content: Plain Mail bodytext
	 */
	protected function makePlain($content) {
		// config
		$htmltagarray = array ( // This tags will be added with linebreaks
			'</p>',
			'</tr>',
			'</li>',
			'</h1>',
			'</h2>',
			'</h3>',
			'</h4>',
			'</h5>',
			'</h6>',
			'</div>',
			'</legend>',
			'</fieldset>',
			'</dd>',
			'</dt>'
		);
		$notallowed = array ( // This array contains not allowed signs which will be removed
			'&nbsp;',
			'&szlig;',
			'&Uuml;',
			'&uuml;',
			'&Ouml;',
			'&ouml;',
			'&Auml;',
			'&auml;',
		);

		// let's go
		$content = nl2br($content);
		$content = str_replace($htmltagarray, $htmltagarray[0] . '<br />', $content); // 1. add linebreaks on some parts (</p> => </p><br />)
		$content = strip_tags($content, '<br><address>'); // 2. remove all tags but not linebreaks and address (<b>bla</b><br /> => bla<br />)
		$content = preg_replace('/\s+/', ' ', $content); // 3. removes tabs and whitespaces
		$content = $this->br2nl($content); // 4. <br /> to \n
		$content = implode("\n", GeneralUtility::trimExplode("\n", $content)); // 5. explode and trim each line and implode again (" bla \n blabla " => "bla\nbla")
		$content = str_replace($notallowed, '', $content); // 6. remove not allowed signs

		return $content;
	}

	/**
	 * Function br2nl is the opposite of nl2br
	 *
	 * @param string $content: Anystring
	 * @return string $content: Manipulated string
	 */
	protected function br2nl($content) {
		$array = array(
			'<br >',
			'<br>',
			'<br/>',
			'<br />'
		);
		$content = str_replace($array, "\n", $content); // replacer

		return $content;
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
		$contentObject = GeneralUtility::makeInstance('tslib_cObj');
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
			$options[] = array(
				'label' => $settings[0],
				'value' => isset($settings[1]) ? $settings[1] : $settings[0],
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
				if (!isset($arr[$answer->getField()][$value])) {
					$arr[$answer->getField()][$value] = 1;
				} else {
					$arr[$answer->getField()][$value]++;
				}
			}
		}

		// sort desc
		foreach ($arr as $key => $value) {
			arsort($arr[$key]);
		}

		// if too much values
		foreach ((array) $arr as $key => $array) {
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
	 * @param object $mails Mails
	 * @param int $max Max Labels
	 * @param string $maxLabel Label for "Max Labels" - could be "all others"
	 * @return array
	 */
	public static function getGroupedMarketingStuff($mails, $max = 10, $maxLabel = 'All others') {
		$arr = array(
			'marketingSearchterm' => array(),
			'marketingReferer' => array(),
			'marketingPayedSearchResult' => array(),
			'marketingLanguage' => array(),
			'marketingBrowserLanguage' => array(),
			'marketingFunnel' => array(),
		);
		foreach ($mails as $mail) {
			foreach ($arr as $key => $v) {
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
			arsort($arr[$key]);
		}

		// if too much values
		foreach ($arr as $key => $array) {
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
	 * Read MarketingInfos from Session
	 *
	 * @return array
	 */
	public static function getMarketingInfos() {
		$info = $GLOBALS['TSFE']->fe_user->getKey('ses', 'powermail_marketing');
		return $info;
	}

	/**
	 * Powermail SendPost - Send values via curl to target
	 *
	 * @param array $fields Params from User
	 * @param array $conf TypoScript Settings
	 * @param object $configurationManager Configuration Manager
	 * @return void
	 */
	public function sendPost($fields, $conf, $configurationManager) {
		if (!$conf['marketing.']['sendPost.']['_enable']) {
			return;
		}
		$fields = $this->getVariablesWithMarkers($fields);
		$cObj = $configurationManager->getContentObject();
		$cObj->start($fields);
		$curl = array(
			'url' => $conf['marketing.']['sendPost.']['targetUrl'],
			'params' => $cObj->cObjGetSingle($conf['marketing.']['sendPost.']['values'], $conf['marketing.']['sendPost.']['values.'])
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $curl['url']);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curl['params']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_exec($ch);
		curl_close($ch);

		// Debug Output
		if ($conf['marketing.']['sendPost.']['debug']) {
			t3lib_utility_Debug::debug($curl, 'powermail debug: Show SendPost Values');
		}
	}

	/**
	 * Returns array with alphabetical letters
	 *
	 * @return array
	 */
	public static function getAbcArray() {
		$arr = array();
		for ($a = A; $a != AA; $a++) { // ABC loop
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
	public static function is_serialized($val) {
		if (!is_string($val) || trim($val) == '') {
			return FALSE;
		}
		if (preg_match('/^(i|s|a|o|d):(.*);/si', $val)) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Check if logged in user is allowed to make changes in Pi2
	 *
	 * @param array $settings $settings TypoScript and Flexform Settings
	 * @param object $mail $mail Mail Object
	 * @return bool
	 */
	public function isAllowedToEdit($settings, $mail) {
		// settings
		if (!$GLOBALS['TSFE']->fe_user->user['uid']) {
			return FALSE;
		}
		$usergroups = GeneralUtility::trimExplode(',', $GLOBALS['TSFE']->fe_user->user['usergroup'], 1); // array with usergroups of current logged in user
		$usersSettings = GeneralUtility::trimExplode(',', $settings['edit']['feuser'], 1); // array with all allowed users
		$usergroupsSettings = GeneralUtility::trimExplode(',', $settings['edit']['fegroup'], 1); // array with all allowed groups

		// replace "_owner" with uid of owner in array with users
		if (method_exists($mail, 'getFeuser') && is_numeric(array_search('_owner', $usersSettings))) {
			$usersSettings[array_search('_owner', $usersSettings)] = $mail->getFeuser();
		}

		// add owner groups to allowed groups (if "_owner")
		if (method_exists($mail, 'getFeuser') && is_numeric(array_search('_owner', $usergroupsSettings))) { // if one entry is "_ownergroup"
			$usergroupsFromOwner = $this->getUserGroupsFromUser($mail->getFeuser()); // get usergroups of owner user
			$usergroupsSettings = array_merge((array) $usergroupsSettings, (array) $usergroupsFromOwner); // add owner usergroups to allowed usergroups array
		}

		// 1. check user
		if (in_array($GLOBALS['TSFE']->fe_user->user['uid'], $usersSettings)) {
			return TRUE;
		}

		// 2. check usergroup
		if (count(array_intersect($usergroups, $usergroupsSettings))) { // if there is one of the groups allowed
			return TRUE;
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
		$where = 'sys_refindex.tablename = "fe_users" AND sys_refindex.ref_table = "fe_groups" AND fe_users.uid = sys_refindex.recuid AND fe_groups.uid = sys_refindex.ref_uid AND fe_users.uid = ' . intval($uid);
		$groupBy = '';
		$orderBy = '';
		$limit = 1000;
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
		if ($res) {
			while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))) { // One loop for every entry
				$groups[] = $row['uid'];
			}
		}

		return $groups;
	}

	/**
	 * Create Hash from String and TYPO3 Encryption Key
	 *
	 * @param string $string Any String
	 * @return string Hashed String
	 */
	public static function createOptinHash($string) {
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
	 * Set a powermail session (don't overwrite existing sessions)
	 *
	 * @param string $name A session name
	 * @param array $values Values to save
	 * @param int $overwrite Overwrite existing values
	 * @return void
	 */
	public static function setSessionValue($name, $values, $overwrite = 0) {
		if (!$overwrite) {
			$oldValues = self::getSessionValue($name); // read existing values
			$values = array_merge((array) $oldValues, (array) $values); // merge old values with new
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
	 * @param string $name A session name
	 * @return mixed Values from session
	 */
	public static function getSessionValue($name = '') {
		$powermailSession = $GLOBALS['TSFE']->fe_user->getKey('ses', self::$extKey);
		if ($name && isset($powermailSession[$name])) {
			return $powermailSession[$name];
		}
		return $powermailSession;
	}

	/**
	 * This is the main-function for sending Mails
	 *
	 * @param array $email Array with all needed mail information
	 * 		$email['receiverName'] = 'Name';
	 * 		$email['receiverEmail'] = 'receiver@mail.com';
	 *		$email['senderName'] = 'Name';
	 * 		$email['senderEmail'] = 'sender@mail.com';
	 * 		$email['subject'] = 'Subject line';
	 * 		$email['template'] = 'PathToTemplate/';
	 * 		$email['rteBody'] = 'This is the <b>content</b> of the RTE';
	 * 		$email['format'] = 'both'; // or plain or html
	 * @param \In2code\Powermail\Domain\Model\Mail $mail Mail object with all arguments
	 * @param array $settings TypoScript Settings
	 * @param string $type Email to "sender" or "receiver"
	 * @return boolean Mail was successfully sent?
	 */
	public function sendTemplateEmail(array $email, \In2code\Powermail\Domain\Model\Mail $mail, $settings, $type = 'receiver') {
		/*****************
		 * Settings
		 ****************/
		$cObj = $this->configurationManager->getContentObject();
		$typoScriptService = $this->objectManager->get('Tx_Extbase_Service_TypoScriptService');
		$conf = $typoScriptService->convertPlainArrayToTypoScriptArray($settings);

		// parsing variables with fluid engine to allow viewhelpers and variables in some flexform fields
		$parse = array(
			'receiverName',
			'receiverEmail',
			'senderName',
			'senderEmail',
			'subject'
		);
		foreach ($parse as $value) {
			$email[$value] = $this->fluidParseString($email[$value], $this->getVariablesWithMarkers($mail));
		}

		// Debug Output
		if ($settings['debug']['mail']) {
			t3lib_utility_Debug::debug($email, 'powermail debug: Show Mail');
		}

		// stop mail process if receiver or sender email is not valid
		if (!GeneralUtility::validEmail($email['receiverEmail']) || !GeneralUtility::validEmail($email['senderEmail'])) {
			return FALSE;
		}

		// stop mail process if subject is empty
		if (empty($email['subject'])) {
			return FALSE;
		}

		// generate mail body
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
		);
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
		$templatePathAndFilename .= $email['template'] . '.html';
		$emailBody = $this->objectManager->get('Tx_Fluid_View_StandaloneView');
		$emailBody->getRequest()->setControllerExtensionName('Powermail');
		$emailBody->getRequest()->setPluginName('Pi1');
		$emailBody->getRequest()->setControllerName('Form');
		$emailBody->setFormat('html');
		$emailBody->setTemplatePathAndFilename($templatePathAndFilename);
		$emailBody->setPartialRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['partialRootPath']));
		$emailBody->setLayoutRootPath(GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['layoutRootPath']));

		// get variables
		// additional variables
		if (isset($email['variables']) && is_array($email['variables'])) {
			$emailBody->assignMultiple($email['variables']);
		}
		// markers in HTML Template
		$variablesWithMarkers = $this->getVariablesWithMarkers($mail);
		$emailBody->assign('variablesWithMarkers', $this->htmlspecialcharsOnArray($variablesWithMarkers));
		$emailBody->assignMultiple($variablesWithMarkers);
		$emailBody->assign('powermail_all', $this->powermailAll($mail, 'mail', $settings));
		// from rte
		$emailBody->assign('powermail_rte', $email['rteBody']);
		$emailBody->assign('marketingInfos', self::getMarketingInfos());
		$emailBody->assign('mail', $mail);
		$emailBody = $emailBody->render();


		/*****************
		 * generate mail
		 ****************/
		$message = GeneralUtility::makeInstance('t3lib_mail_Message');
		$this->overwriteValueFromTypoScript($email['subject'], $this->conf[$type . '.']['overwrite.'], 'subject');
		$message
			->setTo(array($email['receiverEmail'] => $email['receiverName']))
			->setFrom(array($email['senderEmail'] => $email['senderName']))
			->setSubject($email['subject'])
			->setCharset($GLOBALS['TSFE']->metaCharset);

		// add cc receivers
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['cc'], $conf[$type . '.']['overwrite.']['cc.'])) {
			$ccArray = GeneralUtility::trimExplode(',', $cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['cc'], $conf[$type . '.']['overwrite.']['cc.']), 1);
			$message->setCc($ccArray);
		}

		// add bcc receivers
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['bcc'], $conf[$type . '.']['overwrite.']['bcc.'])) {
			$bccArray = GeneralUtility::trimExplode(',', $cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['bcc'], $conf[$type . '.']['overwrite.']['bcc.']), 1);
			$message->setBcc($bccArray);
		}

		// add Return Path
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['returnPath'], $conf[$type . '.']['overwrite.']['returnPath.'])) {
			$message->setReturnPath($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['returnPath'], $conf[$type . '.']['overwrite.']['returnPath.']));
		}

		// add Reply Addresses
		if (
			$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['replyToEmail'], $conf[$type . '.']['overwrite.']['replyToEmail.'])
			&&
			$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['replyToName'], $conf[$type . '.']['overwrite.']['replyToName.'])
		) {
			$replyArray = array(
				$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['replyToEmail'], $conf[$type . '.']['overwrite.']['replyToEmail.']) =>
				$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['replyToName'], $conf[$type . '.']['overwrite.']['replyToName.'])
			);
			$message->setReplyTo($replyArray);
		}

		// add priority
		if ($settings[$type]['overwrite']['priority']) {
			$message->setPriority(intval($settings[$type]['overwrite']['priority']));
		}

		// add attachments from upload fields
		if ($settings[$type]['attachment']) {
			$uploadsFromSession = \In2code\Powermail\Utility\Div::getSessionValue('upload'); // read upload session
			foreach ((array) $uploadsFromSession as $file) {
				$message->attach(Swift_Attachment::fromPath($file));
			}
		}

		// add attachments from TypoScript
		if ($cObj->cObjGetSingle($conf[$type . '.']['addAttachment'], $conf[$type . '.']['addAttachment.'])) {
			$files = GeneralUtility::trimExplode(',', $cObj->cObjGetSingle($conf[$type . '.']['addAttachment'], $conf[$type . '.']['addAttachment.']), 1);
			foreach ($files as $file) {
				$message->attach(Swift_Attachment::fromPath($file));
			}
		}
		if ($email['format'] != 'plain') {
			$message->setBody($emailBody, 'text/html');
		}
		if ($email['format'] != 'html') {
			$message->addPart($this->makePlain($emailBody), 'text/plain');
		}
		$message->send();

		return $message->isSent();
	}

	/**
	 * Merges Flexform and TypoScript Settings (up to 2 levels) and add Global Config from ext_conf_template.txt
	 * 		Why: It's not possible to have the same field in TypoScript and Flexform and if FF value is empty, we want the TypoScript value instead
	 *
	 * @param array $settings All settings
	 * @param string $typoScriptLevel Startpoint
	 * @return array Merged settings
	 */
	public static function mergeTypoScript2FlexForm(&$settings, $typoScriptLevel = 'setup') {
		// config
		$tmp_settings = array();
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['powermail']);

		if (isset($settings[$typoScriptLevel]) && is_array($settings[$typoScriptLevel])) {
			$tmp_settings = $settings[$typoScriptLevel]; // copy typoscript part to conf part
		}

		if (isset($settings['flexform']) && is_array($settings['flexform'])) {
			$tmp_settings = array_merge((array) $tmp_settings, (array) $settings['flexform']); // copy flexform part to conf part
		}

		// merge ts and ff (loop every flexform)
		foreach ($tmp_settings as $key1 => $value1) {
			if (!is_array($value1)) { // 1. level
				if (isset($settings[$typoScriptLevel][$key1]) && isset($settings['flexform'][$key1])) { // only if this key exists in ff and ts
					if ($settings[$typoScriptLevel][$key1] && !$settings['flexform'][$key1]) { // only if ff is empty and ts not
						$tmp_settings[$key1] = $settings[$typoScriptLevel][$key1]; // overwrite with typoscript settings
					}
				}
			} else {
				foreach ($value1 as $key2 => $value2) { // 2. level
					if (isset($settings[$typoScriptLevel][$key1][$key2]) && isset($settings['flexform'][$key1][$key2])) { // only if this key exists in ff and ts
						if ($settings[$typoScriptLevel][$key1][$key2] && !$settings['flexform'][$key1][$key2]) { // only if ff is empty and ts not
							$tmp_settings[$key1][$key2] = $settings[$typoScriptLevel][$key1][$key2]; // overwrite with typoscript settings
						}
					}
				}
			}
		}

		// merge ts and ff (loop every typoscript)
		foreach ((array) $settings[$typoScriptLevel] as $key1 => $value1) {
			if (!is_array($value1)) { // 1. level
				if (isset($settings[$typoScriptLevel][$key1]) && !isset($settings['flexform'][$key1])) { // only if this key exists in ts and not in ff
					$tmp_settings[$key1] = $value1; // set value from ts
				}
			} else {
				foreach ($value1 as $key2 => $value2) { // 2. level
					if (isset($settings[$typoScriptLevel][$key1][$key2]) && !isset($settings['flexform'][$key1][$key2])) { // only if this key exists in ts and not in ff
						$tmp_settings[$key1][$key2] = $value2; // set value from ts
					}
				}
			}
		}

		// add global config
		$tmp_settings['global'] = $confArr;

		$settings = $tmp_settings;
		unset($tmp_settings);
	}

}
