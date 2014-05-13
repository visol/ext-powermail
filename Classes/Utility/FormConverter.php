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
 * Converts old to new forms
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class FormConverter {

	/**
	 * Create new forms from old ones
	 *
	 * @param array $oldFormsWithFieldsetsAndFields
	 * @param array $configuration
	 * @param bool $dryrun
	 * @return array result
	 */
	public static function createNewFromOldForms($oldFormsWithFieldsetsAndFields, $configuration, $dryrun = TRUE) {
		$configuration['save'] = 48;
		$configuration['hidden'] = '1';

//		$x = self::rewriteVarialbes('das ist ###UID123### toll');

		if (!$dryrun) {
			GeneralUtility::devLog(
				'Old Forms to convert',
				'powermail',
				0,
				$oldFormsWithFieldsetsAndFields
			);
		}

		// create forms
		$result = array();
		$formCounter = 0;
		foreach ((array) $oldFormsWithFieldsetsAndFields as $form) {
			// ignore hidden forms
			if ($form['hidden'] === '1' && $configuration['hidden'] === '1') {
				continue;
			}

			$formUid = 0;
			$formProperties = array(
				'pid' => ($configuration['save'] === '[samePage]' ? $form['pid'] : intval($configuration['save'])),
				'title' => $form['tx_powermail_title'],
				'pages' => $form['tx_powermail_fieldsets'],
				'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
				'hidden' => $form['hidden'],
				'l10n_parent' => 0, // TODO
				'sys_language_uid' => 0 // TODO
			);
			$result[$formCounter] = $formProperties;
			if (!$dryrun) {
				$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_powermail_domain_model_forms', $formProperties);
				$formUid = $GLOBALS['TYPO3_DB']->sql_insert_id();
			}

			// create pages
			$pageCounter = 0;
			foreach ((array) $form['fieldsets'] as $page) {
				$pageUid = 0;
				$pageProperties = array(
					'pid' => ($configuration['save'] === '[samePage]' ? $form['pid'] : intval($configuration['save'])),
					'forms' => $formUid,
					'title' => $page['title'],
					'css' => $page['class'],
					'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
					'hidden' => $page['hidden'],
					'sorting' => $page['sorting'],
					'l10n_parent' => 0, // TODO
					'sys_language_uid' => 0 // TODO
				);
				$result[$formCounter]['fieldsets'][$pageCounter] = $pageProperties;
				if (!$dryrun) {
					$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_powermail_domain_model_pages', $pageProperties);
					$pageUid = $GLOBALS['TYPO3_DB']->sql_insert_id();
				}

				// create fields
				$fieldCounter = 0;
				foreach ((array) $page['fields'] as $field) {
					if (!self::rewriteFormType($field)) {
						continue;
					}
					$fieldUid = 0;
					$fieldProperties = array(
						'pid' => ($configuration['save'] === '[samePage]' ? $form['pid'] : intval($configuration['save'])),
						'pages' => $pageUid,
						'title' => $field['title'],
						'type' => self::rewriteFormType($field),
						'css' => self::rewriteStyles($field['class']),
						'cruser_id' => $GLOBALS['BE_USER']->user['uid'],
						'hidden' => $field['hidden'],
						'sorting' => $field['sorting'],
						'marker' => 'UID' . $field['uid'],
						'settings' => $field['options'],
						'path' => $field['path'],
						'content_element' => $field['path'],
						'text' => $field['value'],
						'prefill_value' => $field['value'],
						'mandatory' => $field['mandatory'],
						'validation' => self::rewriteValidation($field['validate'], $field['inputtype']),
						'validation_configuration' => self::rewriteValidationConfiguration($field),
						'l10n_parent' => 0, // TODO
						'sys_language_uid' => 0 // TODO
					);
					$result[$formCounter]['fieldsets'][$pageCounter]['fields'][$fieldCounter] = $fieldProperties;
					if (!$dryrun) {
						$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_powermail_domain_model_fields', $fieldProperties);
						$fieldUid = $GLOBALS['TYPO3_DB']->sql_insert_id();
					}
					$fieldCounter++;
				}
				$pageCounter++;
			}
			$formCounter++;
		}

		return $result;
	}

	/**
	 * Reformat fieldtypes from old to new
	 *
	 * @param array $field
	 * @return bool|string
	 */
	public static function rewriteFormType($field) {
		if ($field['inputtype'] === 'time') {
			return 'date';
		}

		$formTypes = array(
			'text' => 'input',
			'textarea' => 'textarea',
			'select' => 'select',
			'check' => 'check',
			'radio' => 'radio',
			'submit' => 'submit',
			'captcha' => 'captcha',
			'reset' => FALSE,
			'label' => 'text',
			'content' => 'content',
			'html' => 'html',
			'password' => 'password',
			'file' => 'file',
			'hidden' => 'hidden',
			'datetime' => 'date',
			'date' => 'date',
			'button' => FALSE,
			'submitgraphic' => 'submit',
			'countryselect' => 'country',
			'typoscript' => 'typoscript'
		);
		if (array_key_exists($field['formtype'], $formTypes)) {
			return $formTypes[$field['formtype']];
		}

		return FALSE;
	}

	/**
	 * Reformat styles
	 *
	 * @param $oldStyle
	 * @return string
	 */
	public static function rewriteStyles($oldStyle) {
		$styleTypes = array(
			'style1' => 'layout1',
			'style2' => 'layout2',
			'style3' => 'layout3'
		);
		if (array_key_exists($oldStyle, $styleTypes)) {
			return $styleTypes[$oldStyle];
		}
		return '';
	}

	/**
	 * Reformat validation
	 *
	 * @param string $oldString
	 * @param string $inputtype
	 * @return string
	 */
	public static function rewriteValidation($oldString, $inputtype) {
		$newStrings = array(
			'validate-email' => '1',
			'validate-url' => '2',
			'validate-number' => '4',
			'validate-digits' => '',
			'validate-alpha' => '5',
			'validate-alphanum' => '',
			'validate-pattern' => '10',
			'validate-alpha-w-umlaut' => '5',
			'validate-alphanum-w-umlaut' => ''
		);
		if (array_key_exists($oldString, $newStrings)) {
			return $newStrings[$oldString];
		}

		$newStrings = array(
			'color' => '',
			'range' => '8',
			'tel' => '3',
			'time' => ''
		);
		if (array_key_exists($inputtype, $newStrings)) {
			return $newStrings[$inputtype];
		}

		return '';
	}

	/**
	 * @param array $field
	 * @return string
	 */
	public static function rewriteValidationConfiguration($field) {
		if ($field['validate'] === 'validate-pattern') {
			if (!empty($field['pattern'])) {
				return $field['pattern'];
			}
		}
		if ($field['inputtype'] === 'range') {
			return '0,10';
		}
		return '';
	}

	/**
	 * Convert old to new marker
	 * 		from: this is the ###uid123### value
	 * 		to: this is the {uid123} value
	 *
	 * @param string $string
	 * @return string
	 */
	public static function rewriteVarialbes($string) {
		$string = preg_replace('|###(.*)?###|i', '{$1}', $string);
		return $string;
	}
}