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
 * Class to extend Pi1 field marker e.g. {firstname}
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 *
 */
class InitialMarker extends \In2code\Powermail\Utility\MarkerBase {

	/**
	 * Initialy filling of marker field
	 *
	 * @param \string $status mode of change
	 * @param \string $table the table which gets changed
	 * @param \string $uid uid of the record
	 * @param \array $fieldArray the updateArray
	 * @param \array $pObj
	 * @return an updated $fieldArray
	 */
	public function processDatamapPostProcessFieldArray($status, $table, $uid, &$fieldArray, $pObj) {
		// stop if not powermail field table
		if ($table != 'tx_powermail_domain_model_fields') {
			return $fieldArray;
		}

		// get large array with markers (from db and new)
		$markers = array_merge((array) $this->existingMarkers, (array) $this->marker);
		// get array with unique markers
		$this->makeUniqueValueInArray($markers);

		// marker should be changed OR this is a new field
		if (isset($this->data['tx_powermail_domain_model_fields'][$uid]['marker']) || stristr($uid, 'NEW')) {
			// rewrite
			$fieldArray['marker'] = $markers['_' . $uid];
		}

		return $fieldArray;
	}
}