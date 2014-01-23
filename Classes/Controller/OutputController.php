<?php

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
 * Controller for powermail frontend output (former part of the powermail_frontend extension)
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class Tx_Powermail_Controller_OutputController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Powermail_Domain_Repository_MailRepository
	 *
	 * @inject
	 */
	protected $mailRepository;

	/**
	 * @var Tx_Powermail_Domain_Repository_FormRepository
	 *
	 * @inject
	 */
	protected $formRepository;

	/**
	 * @var Tx_Powermail_Domain_Repository_FieldRepository
	 *
	 * @inject
	 */
	protected $fieldRepository;

	/**
	 * @var Tx_Powermail_Domain_Repository_AnswerRepository
	 *
	 * @inject
	 */
	protected $answerRepository;

	/**
	 * @var Tx_Powermail_Utility_Div
	 *
	 * @inject
	 */
	protected $div;

	/**
	 * Request arguments
	 *
	 * @var array
	 */
	protected $piVars;

	/**
	 * Show mails in a list
	 *
	 * @return void
	 */
	public function listAction() {
		// get all mails
		$mails = $this->mailRepository->findListBySettings($this->settings, $this->piVars);
		$this->view->assign('mails', $mails);

		// get field array for output
		$fields = t3lib_div::trimExplode(',', $this->settings['list']['fields'], 1);
		if (!$fields) {
			$fields = $this->div->getFieldsFromForm($this->settings['main']['form']);
		}
		$this->view->assign('fields', $fields);

		// get piVars
		$this->view->assign('piVars', $this->piVars);
		$this->view->assign('abc', Tx_Powermail_Utility_Div::getAbcArray());

		// single pid
		if (empty($this->settings['single']['pid'])) {
			$this->settings['single']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('singlePid', $this->settings['single']['pid']);

		// edit pid
		if (empty($this->settings['edit']['pid'])) {
			$this->settings['edit']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('editPid', $this->settings['edit']['pid']);
	}

	/**
	 * Show mails in a list
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	public function showAction(\In2code\Powermail\Domain\Model\Mail $mail) {
		$this->view->assign('mail', $mail);

		// get field array for output
		$fields = t3lib_div::trimExplode(',', $this->settings['detail']['fields'], 1);
		if (!$fields) {
			$fields = $this->div->getFieldsFromForm($this->settings['main']['form']);
		}
		$this->view->assign('fields', $fields);

		// list pid
		if (empty($this->settings['list']['pid'])) {
			$this->settings['list']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('listPid', $this->settings['list']['pid']);

		// edit pid
		if (empty($this->settings['edit']['pid'])) {
			$this->settings['edit']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('editPid', $this->settings['edit']['pid']);
	}

	/**
	 * Edit mail
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	public function editAction(\In2code\Powermail\Domain\Model\Mail $mail) {
		$this->view->assign('mail', $mail);

		// get field array for output
		$fields = t3lib_div::trimExplode(',', $this->settings['edit']['fields'], 1);
		if (!$fields) {
			$fields = $this->div->getFieldsFromForm($this->settings['main']['form']);
		}
		foreach ((array) $fields as $key => $field) {
			$fields[$key] = $this->fieldRepository->findByUid($field);
		}
		$this->view->assign('fields', $fields);

		// list pid
		if (empty($this->settings['list']['pid'])) {
			$this->settings['list']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('listPid', $this->settings['list']['pid']);

		// single pid
		if (empty($this->settings['single']['pid'])) {
			$this->settings['single']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('singlePid', $this->settings['single']['pid']);
	}

	/**
	 * Update mail
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param array $field Field Array with changes
	 * @dontvalidate $mail
	 * @dontvalidate $field
	 * @return void
	 */
	public function updateAction(\In2code\Powermail\Domain\Model\Mail $mail, $field = array()) {
		if ($this->div->isAllowedToEdit($this->settings, $mail)) {
			// one loop for every received field
			foreach ((array) $field as $fieldUid => $value) {
				$answer = $this->answerRepository->findByFieldAndMail($fieldUid, $mail);
				$answer->setValue($value);
				$this->answerRepository->update($answer);
			}
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('PowermailFrontendEditConfirm', 'powermail'));
		} else {
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('PowermailFrontendEditFailed', 'powermail'));
		}

		$this->redirect('edit', NULL, NULL, array('mail' => $mail));
	}

	/**
	 * Export mails
	 *
	 * @param array $export Field Array with mails and format
	 * @dontvalidate $export
	 * @return void
	 */
	public function exportAction($export = array()) {
		if (!$this->settings['list']['export']) {
			return;
		}
		$mails = $this->mailRepository->findByUidList($export['fields']);

		// get field array for output
		$fields = t3lib_div::trimExplode(',', $this->settings['list']['fields'], 1);
		if (!$fields) {
			$fields = $this->div->getFieldsFromForm($this->settings['main']['form']);
		}

		if ($export['format'] == 'xls') {
			$this->forward('exportXls', NULL, NULL, array('mails' => $mails, 'fields' => $fields));
		}
		$this->forward('exportCsv', NULL, NULL, array('mails' => $mails, 'fields' => $fields));
	}

	/**
	 * Export mails XLS
	 *
	 * @param		array		$mails mails objects
	 * @param		array		$fields uid field list
	 * @dontvalidate $mails
	 * @dontvalidate $fields
	 * @return 	void
	 */
	public function exportXlsAction($mails = array(), $fields = array()) {
		$this->view->assign('mails', $mails);
		$this->view->assign('fields', $fields);
	}

	/**
	 * Export mails CSV
	 *
	 * @param array $mails mails objects
	 * @param array $fields uid field list
	 * @dontvalidate $mails
	 * @dontvalidate $fields
	 * @return void
	 */
	public function exportCsvAction($mails = array(), $fields = array()) {
		$this->view->assign('mails', $mails);
		$this->view->assign('fields', $fields);
	}

	/**
	 * RSS Action List
	 *
	 * @return void
	 */
	public function rssAction() {
		$mails = $this->mailRepository->findListBySettings($this->settings, $this->piVars);
		$this->view->assign('mails', $mails);

		// single pid
		if (empty($this->settings['single']['pid'])) {
			$this->settings['single']['pid'] = $GLOBALS['TSFE']->id;
		}
		$this->view->assign('singlePid', $this->settings['single']['pid']);
	}

	/**
	 * Deactivate errormessages in flashmessages
	 *
	 * @return bool
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}

	/**
	 * Object initialization
	 *
	 * @return void
	 */
	public function initializeObject() {
		// merge typoscript to flexform
		Tx_Powermail_Utility_Div::mergeTypoScript2FlexForm($this->settings, 'Pi2');

		// check if ts is included
		if (!isset($this->settings['staticTemplate'])) {
			$this->flashMessageContainer->add(\TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('error_no_typoscript_pi2', 'powermail'));
		}
	}

	/**
	 * Action initialization
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->piVars = $this->request->getArguments();
	}

}