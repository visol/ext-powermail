<?php
namespace In2code\Powermail\Domain\Repository;

use \TYPO3\CMS\Core\Utility\GeneralUtility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 in2code GmbH <info@in2code.de>
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
 * FormRepository
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class FormRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {

	/**
	 * Find Form objects by its given uids
	 *
	 * @param string $uids commaseparated list of uids
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	public function findByUids($uids) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->getQuerySettings()->setRespectSysLanguage(FALSE);

		$query->matching(
			$query->in('uid', GeneralUtility::trimExplode(',', $uids, 1))
		);

		$result = $query->execute();
		return $result;
	}

	/**
	 * Returns form with captcha from given UID
	 *
	 * @param \In2code\Powermail\Domain\Model\Form $form
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\QueryResult
	 */
	public function hasCaptcha(\In2code\Powermail\Domain\Model\Form $form) {
		$query = $this->createQuery();
		// disable storage pid
		$query->getQuerySettings()->setRespectStoragePage(FALSE);

		$and = array(
			$query->equals('uid', $form->getUid()),
			$query->equals('pages.fields.type', 'captcha')
		);

		// create where object with AND
		$constraint = $query->logicalAnd($and);
		// use constraint
		$query->matching($constraint);

		return $query->execute();
	}

	/**
	 * This function is a workarround to get the field value of
	 * "pages" in the table "forms"
	 * (only relevant if IRRE was replaced by Element Browser)
	 *
	 * @param int $uid Form UID
	 * @return string
	 */
	public function getPagesValue($uid) {
		$query = $this->createQuery();
		$query->getQuerySettings()->setReturnRawQueryResult(TRUE);

		// create sql statement
		$sql = 'select pages';
		$sql .= ' from tx_powermail_domain_model_forms';
		$sql .= ' where uid = ' . intval($uid);
		$sql .= ' limit 1';

		$result = $query->statement($sql)->execute();

		return $result[0]['pages'];
	}
}