<?php
namespace In2code\Powermail\Utility;

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
 * This class allows you to save values to any table
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class SaveToTable {

	/**
	 * cObj
	 *
	 * @var Content Object
	 */
	protected $cObj;

	/**
	 * TypoScript configuration
	 *
	 * @var configuration
	 */
	protected $conf;

	/**
	 * All Plugin Params
	 *
	 * @var array
	 */
	protected $allArguments;

	/**
	 * Debug Array
	 *
	 * @var array
	 */
	protected $debugArray;

	/**
	 * Values to store
	 *
	 * @var array
	 */
	protected $dbValues;

	/**
	 * Stop db insert for testing
	 *
	 * @var boolean
	 */
	protected $dbInsert = 1;

	/**
	 * @var \int
	 */
	protected $uid;

	/**
	 * @var array
	 */
	protected $dbValuesMm = array();

	/**
	 * Preflight method to store values to any db table
	 *
	 * @param \array $allArguments
	 * @param \array $conf
	 * @param \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj
	 * @param \bool $ok
	 * @return void
	 */
	public function main($allArguments, $conf, $cObj, $ok = TRUE) {
		$this->cObj = $cObj;
		$this->conf = $conf;
		$this->allArguments = $allArguments;

		if (!$ok || !isset($this->conf['dbEntry.']) || !is_array($this->conf['dbEntry.'])) {
			return;
		}

		// One loop for every table to insert
		foreach ($this->conf['dbEntry.'] as $table => $value) {
			$value = NULL;

			if ($this->cObj->cObjGetSingle($this->conf['dbEntry.'][$table]['_enable'], $this->conf['dbEntry.'][$table]['_enable.']) != 1) {
				continue;
			}

			// 1. Array for first db entry
			// One loop for every field to insert in current table
			foreach ((array) $this->conf['dbEntry.'][$table] as $field => $value2) {
				$value2 = NULL;

				// if fieldname is _enable or _mm and not with . at the end
				if ($field[0] == '_' || substr($field, -1) == '.') {
					// go to next loop
					continue;
				}

				// if db table and field exists
				if ($this->fieldExists($field, $this->removeDot($table))) {
					// push to ts
					$this->cObj->start($allArguments);
					// write current TS value to array
					$this->dbValues[$table][$field] = $this->cObj->cObjGetSingle(
						$this->conf['dbEntry.'][$table][$field], $this->conf['dbEntry.'][$table][$field . '.']
					);
				}
			}

			// 2. DB insert
			// Main DB entry for every table
			$this->dbUpdate($this->removeDot($table), $this->dbValues[$table]);

			// 2.1 db entry for mm tables if set
			// if mm entry enabled
			if (count($this->conf['dbEntry.'][$table]['_mm.']) > 0) {
				// One loop for every mm db insert
				foreach ($this->conf['dbEntry.'][$table]['_mm.'] as $keyMm => $valueMm) {
					$valueMm = NULL;

					// We want the array
					if (substr($keyMm, -1) == '.') {
						// 1. is db table && 2. is db table && 3. is a number
						if (
							$this->fieldExists(
								'uid_local',
								$this->cObj->cObjGetSingle(
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['1'],
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['1.']
								)
							)
							&&
							$this->fieldExists(
								'uid',
								$this->cObj->cObjGetSingle(
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['2'],
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['2.']
								)
							)
							&&
							is_numeric(
								$this->cObj->cObjGetSingle(
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['3'],
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['3.']
								)
							)
						) {
							// if uid_local exists
							if ($this->uid[str_replace('.', '', $table)] > 0) {
								$this->dbValuesMmm[$table] = array (
									'uid_local' => $this->uid[str_replace('.', '', $table)],
									'uid_foreign' => $this->cObj->cObjGetSingle(
										$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['3'],
										$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['3.']
									)
								);
							}
							// DB entry for every table
							if (count($this->dbValuesMmm[$table]) > 0) {
								$this->dbUpdate(
									$this->cObj->cObjGetSingle($this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['1'],
									$this->conf['dbEntry.'][$table]['_mm.'][$keyMm]['1.']),
									$this->dbValuesMmm[$table]
								);
							}
						}
					}
				}
			}
		}

		$this->debug();
	}

	/**
	 * Function dbUpdate() inserts or updates database
	 *
	 * @param \string $table
	 * @param \array $values
	 * @return void
	 */
	protected function dbUpdate($table, $values) {
		// if there are values
		if (count($values) == 0) {
			return;
		}
		// no unique values
		if (!isset($this->conf['dbEntry.'][$table . '.']['_ifUnique.']) ||
			$this->conf['dbEntry.'][$table . '.']['_ifUnique.'] == 'disable') {
			// if allowed
			if ($this->dbInsert) {
				// DB entry for every table
				$GLOBALS['TYPO3_DB']->exec_INSERTquery($table, $values);
				// Get uid of current db entry
				$this->uid[$table] = $GLOBALS['TYPO3_DB']->sql_insert_id();
			}
		// unique values
		} else {
			// get first entry of this array
			$uniqueField = key($this->conf['dbEntry.'][$table . '.']['_ifUnique.']);
			// mode could be "none" or "update"
			$mode = $this->conf['dbEntry.'][$table . '.']['_ifUnique.'][$uniqueField];
			// check if field uid exists in table
			if ($this->fieldExists('uid', $table)) {
				// get uid of existing value
				$select = 'uid';
				$from = $table;
				$where = $uniqueField;
				$where .= ' = "';
				$where .= $this->cObj->cObjGetSingle($this->conf['dbEntry.'][$table . '.'][$uniqueField],
						$this->conf['dbEntry.'][$table . '.'][$uniqueField . '.']);
				$where .= '"';
				$where .= ($this->fieldExists('deleted', $table) ? ' AND deleted = 0' : '');
				$groupBy = '';
				$orderBy = '';
				$limit = 1;
				$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $from, $where, $groupBy, $orderBy, $limit);
				if ($res) {
					$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
				}
			}

			// there is already an entry in the database
			if (isset($row) && $row['uid'] > 0) {
				switch ($mode) {
					case 'update':
						// update old entry with new values
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, 'uid = ' . intval($row['uid']), $values);
						// Make row uid global
						$this->uid[$table] = $row['uid'];
						break;

					case 'none':
					default:
						// do nothing
						$this->dbValues = 'Entry already exists, won\'t be overwritten';
						break;
				}
			// there is no entry in the database
			} else {
				// New DB entry
				$GLOBALS['TYPO3_DB']->exec_INSERTquery($table, $values);
				// Get uid of current db entry
				$this->uid[$table] = $GLOBALS['TYPO3_DB']->sql_insert_id();
			}

		}
	}

	/**
	 * Function fieldExists() checks if a table and field exist in mysql db
	 *
	 * @param \string $field
	 * @param \string $table
	 * @return bool
	 */
	protected function fieldExists($field = '', $table = '') {
		if (empty($field) || empty($table) || stristr($field, '.')) {
			return FALSE;
		}

		// check if table and field exits in db
		$allTables = $GLOBALS['TYPO3_DB']->admin_get_tables();
		$tableInfo = $allTables[$table];
		if (is_array($tableInfo)) {
			// check if field exist (if table is wront - errormessage)
			$allFields = $GLOBALS['TYPO3_DB']->admin_get_fields($table);

			$fieldInfo = $allFields[$field];
		}

		// debug values
		if (!is_array($tableInfo)) {
			// errormessage if table don't exits
			$this->debugArray['ERROR'][] = 'Table "' . $table . '" don\'t exists in db';
		}
		if (is_array($tableInfo) && !is_array($fieldInfo)) {
			// errormessage if field don't exits
			$this->debugArray['ERROR'][] = 'Field "' . $field . '" don\'t exists in db table "' . $table . '"';
		}

		// return true or false
		if (is_array($tableInfo) && is_array($fieldInfo)) {
			// table and field exist
			return TRUE;
		} else {
			// table or field don't exist
			return FALSE;
		}
		return FALSE;
	}

	/**
	 * Remove . from string
	 *
	 * @param	string	string with a .
	 * @return	string	string without any .
	 */
	protected function removeDot($string) {
		return str_replace('.', '', $string);
	}

	/**
	 * Pushes out some debug messages
	 *
	 * @return void
	 */
	protected function debug() {
		// Debug Output

		// array for debug view
		$this->debugArray['Main Table'] = $this->dbValues;
		// array for debug view
		$this->debugArray['MM Table'] = (count($this->db_values_mm) > 0 ? $this->db_values_mm : 'no values or entry already exists');

		if ($this->conf['debug.']['saveToTable']) {
			\TYPO3\CMS\Core\Utility\DebugUtility::debug($this->debugArray, 'powermail debug: Show Values from "SaveToTable" Function');
		}
	}
}