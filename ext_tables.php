<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

use \TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Get configuration from extension manager
 */
$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['powermail']);

/**
 * Include Plugins
 */
	// Pi1
ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Powermail'
);
	// Pi2
ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi2',
	'Powermail_Frontend'
);

/**
 * Include Backend Module
 */
if (TYPO3_MODE === 'BE' && !$confArr['disableBackendModule'] && !(TYPO3_REQUESTTYPE & TYPO3_REQUESTTYPE_INSTALL)) {
	ExtensionUtility::registerModule(
		'In2code.' . $_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'm1',	 // Submodule key
		'',		 // Position
		array(
			'Module' => 'listBe, checkBe, exportBe, reportingBe, reportingFormBe, reportingMarketingBe'
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xml',
		)
	);
}

/**
 * Include Flexform
 */
	// Pi1
$pluginSignature = str_replace('_', '', $_EXTKEY) . '_pi1';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi1.xml');
	// Pi2
$pluginSignature = str_replace('_', '', $_EXTKEY) . '_pi2';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi2.xml');

/**
 * Include UserFuncs
 */
if (TYPO3_MODE == 'BE') {
	// show powermail fields in Pi2 (powermail_frontend)
	require_once(ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Utility/FieldSelectorUserFunc.php');

	// marker field in Pi1
	require_once(ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Utility/Marker.php');

	// add options to TCA select fields with itemsProcFunc
	require_once(ExtensionManagementUtility::extPath($_EXTKEY) . 'Classes/Utility/FlexFormFieldSelection.php');

	// WizIcon for Pi1
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_powermail_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'Classes/Utility/WizIcon.php';
}

/**
 * Include TypoScript
 */
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Main', 'Main Template');
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Powermail_Frontend', 'Powermail_Frontend');
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/CssBasic', 'Add basic CSS');
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/CssFancy', 'Add fancy CSS');
ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Marketing', 'Marketing Information');

/**
 * Settings for Tables
 */
ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermail_domain_model_forms', 'EXT:powermail/Resources/Private/Language/locallang_csh_tx_powermail_domain_model_forms.xml');
ExtensionManagementUtility::allowTableOnStandardPages('tx_powermail_domain_model_forms');
$TCA['tx_powermail_domain_model_forms'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xml:tx_powermail_domain_model_forms',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY title ASC',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Form.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_powermail_domain_model_forms.gif'
	),
);

ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermail_domain_model_pages', 'EXT:powermail/Resources/Private/Language/locallang_csh_tx_powermail_domain_model_pages.xml');
ExtensionManagementUtility::allowTableOnStandardPages('tx_powermail_domain_model_pages');
$TCA['tx_powermail_domain_model_pages'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xml:tx_powermail_domain_model_pages',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'sortby' => 'sorting',
		'default_sortby' => 'ORDER BY sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Page.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_powermail_domain_model_pages.gif'
	),
);

ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermail_domain_model_fields', 'EXT:powermail/Resources/Private/Language/locallang_csh_tx_powermail_domain_model_fields.xml');
ExtensionManagementUtility::allowTableOnStandardPages('tx_powermail_domain_model_fields');
$TCA['tx_powermail_domain_model_fields'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xml:tx_powermail_domain_model_fields',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'sortby' => 'sorting',
		'default_sortby' => 'ORDER BY sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'requestUpdate' => 'type,own_marker_select',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Field.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_powermail_domain_model_fields.gif'
	),
);

ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermail_domain_model_mails', 'EXT:powermail/Resources/Private/Language/locallang_csh_tx_powermail_domain_model_mails.xml');
ExtensionManagementUtility::allowTableOnStandardPages('tx_powermail_domain_model_mails');
$TCA['tx_powermail_domain_model_mails'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xml:tx_powermail_domain_model_mails',
		'label' => 'sender_mail',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate DESC',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Mail.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_powermail_domain_model_mails.gif',
		'searchFields' => 'sender_mail, sender_name, subject, body'
	),
);

ExtensionManagementUtility::addLLrefForTCAdescr('tx_powermail_domain_model_answers', 'EXT:powermail/Resources/Private/Language/locallang_csh_tx_powermail_domain_model_answers.xml');
ExtensionManagementUtility::allowTableOnStandardPages('tx_powermail_domain_model_answers');
$TCA['tx_powermail_domain_model_answers'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:powermail/Resources/Private/Language/locallang_db.xml:tx_powermail_domain_model_answers',
		'label' => 'value',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate DESC',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Answer.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_powermail_domain_model_answers.gif'
	),
);