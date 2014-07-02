<?php
namespace In2code\Powermail\Controller;

use \In2code\Powermail\Utility\Div,
	\In2code\Powermail\Utility\FormConverter,
	\TYPO3\CMS\Core\Utility\GeneralUtility;

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
 * Controller for powermail list views (BE and FE)
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class ModuleController extends \In2code\Powermail\Controller\AbstractController {

	/**
	 * Request arguments
	 *
	 * @var array
	 */
	protected $piVars;

	/**
	 * List View Backend
	 *
	 * @return void
	 */
	public function listBeAction() {
		$mails = $this->mailRepository->findAllInPid(GeneralUtility::_GP('id'), $this->settings, $this->piVars);
		$firstMail = $this->mailRepository->findFirstInPid(GeneralUtility::_GP('id'));

		$this->view->assign('mails', $mails);
		$this->view->assign('firstMail', $firstMail);
		$this->view->assign('piVars', $this->piVars);
		$this->view->assign('pid', GeneralUtility::_GP('id'));
		$this->view->assign('token', \TYPO3\CMS\Backend\Utility\BackendUtility::getUrlToken('tceAction'));
		$this->view->assign('perPage', ($this->settings['perPage'] ? $this->settings['perPage'] : 10));
	}

	/**
	 * Reporting
	 *
	 * @param string $subaction could be 'form' or 'marketing'
	 * @return void
	 */
	public function reportingBeAction($subaction = NULL) {
		if ($subaction == 'form') {
			$this->forward('reportingFormBe');
		}
		if ($subaction == 'marketing') {
			$this->forward('reportingMarketingBe');
		}
	}

	/**
	 * Tools overview
	 *
	 * @return void
	 */
	public function toolsBeAction() {
	}

	/**
	 * Form Overview
	 *
	 * @return void
	 */
	public function overviewBeAction() {
		$pid = GeneralUtility::_GET('id');
		$forms = $this->formRepository->findAllInPid($pid);
		$this->view->assign('forms', $forms);
		$this->view->assign('pid', $pid);
	}

	/**
	 * Check View Backend
	 *
	 * @param string $email email address
	 * @return void
	 */
	public function checkBeAction($email = NULL) {
		$this->view->assign('pid', GeneralUtility::_GP('id'));

		if ($email) {
			if (GeneralUtility::validEmail($email)) {
				$body = 'New <b>Test Email</b> from User ';
				$body .= $GLOBALS['BE_USER']->user['username'] . ' (' . GeneralUtility::getIndpEnv('HTTP_HOST') . ')';

				$message = GeneralUtility::makeInstance('\TYPO3\CMS\Core\Mail\MailMessage');
				$message
					->setTo(array($email => 'Receiver'))
					->setFrom(array('powermail@domain.net' => 'powermail'))
					->setSubject('New Powermail Test Email')
					->setBody($body, 'text/html')
					->send();

				$this->view->assign('issent', $message->isSent());
				$this->view->assign('email', $email);
			}
		}
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function initializeConverterBeAction() {
		if (!Div::isBackendAdmin()) {
			$this->controllerContext = $this->buildControllerContext();
			$this->forward('toolsBe');
		}
	}

	/**
	 * Convert all old forms preflight
	 *
	 * @return void
	 */
	public function converterBeAction() {
		$oldForms = $this->formRepository->findAllOldForms();
		$this->view->assign('oldForms', $oldForms);
	}

	/**
	 * Init
	 *
	 * @return void
	 */
	public function initializeConverterUpdateBeAction() {
		if (!Div::isBackendAdmin()) {
			$this->controllerContext = $this->buildControllerContext();
			$this->forward('toolsBe');
		}
	}

	/**
	 * Convert all old forms
	 *
	 * @param array $converter
	 * @return void
	 */
	public function converterUpdateBeAction($converter) {
		$oldForms = $this->formRepository->findAllOldForms();
		$formCounter = 0;
		$oldFormsWithFieldsetsAndFields = array();
		foreach ($oldForms as $form) {
			$oldFormsWithFieldsetsAndFields[$formCounter] = $form;
			$oldFormsWithFieldsetsAndFields[$formCounter]['_fieldsets'] =
				$this->formRepository->findOldFieldsetsAndFieldsToTtContentRecord($form['uid']);
			$formCounter++;
		}
		/** @var \In2code\Powermail\Utility\FormConverter $formConverter */
		$formConverter = $this->objectManager->get('\In2code\Powermail\Utility\FormConverter');
		$result = $formConverter->createNewFromOldForms($oldFormsWithFieldsetsAndFields, $converter);
		$this->view->assign('result', $result);
		$this->view->assign('converter', $converter);
	}

	/**
	 * Reporting Form
	 *
	 * @return void
	 */
	public function reportingFormBeAction() {
		$mails = $this->mailRepository->findAllInPid(GeneralUtility::_GP('id'), $this->settings, $this->piVars);
		$firstMail = $this->mailRepository->findFirstInPid(GeneralUtility::_GP('id'));
		$groupedAnswers = Div::getGroupedMailAnswers($mails);

		$this->view->assign('groupedAnswers', $groupedAnswers);
		$this->view->assign('mails', $mails);
		$this->view->assign('firstMail', $firstMail);
		$this->view->assign('piVars', $this->piVars);
		$this->view->assign('pid', GeneralUtility::_GP('id'));
		$this->view->assign('token', \TYPO3\CMS\Backend\Utility\BackendUtility::getUrlToken('tceAction'));
		$this->view->assign('perPage', ($this->settings['perPage'] ? $this->settings['perPage'] : 10));
	}

	/**
	 * Reporting Marketing
	 *
	 * @return void
	 */
	public function reportingMarketingBeAction() {
		$mails = $this->mailRepository->findAllInPid(GeneralUtility::_GP('id'), $this->settings, $this->piVars);
		$firstMail = $this->mailRepository->findFirstInPid(GeneralUtility::_GP('id'));
		$groupedMarketingStuff = Div::getGroupedMarketingStuff($mails);

		$this->view->assign('groupedMarketingStuff', $groupedMarketingStuff);
		$this->view->assign('mails', $mails);
		$this->view->assign('firstMail', $firstMail);
		$this->view->assign('piVars', $this->piVars);
		$this->view->assign('pid', GeneralUtility::_GP('id'));
		$this->view->assign('token', \TYPO3\CMS\Backend\Utility\BackendUtility::getUrlToken('tceAction'));
		$this->view->assign('perPage', ($this->settings['perPage'] ? $this->settings['perPage'] : 10));
	}

	/**
	 * Export Action
	 *
	 * @param array $export export settings
	 * @return void
	 */
	public function exportBeAction(array $export = array()) {
		if ($export['format'] == 'xls') {
			$this->forward('exportXlsBe', NULL, NULL, array('export' => $export));
		}
		$this->forward('exportCsvBe', NULL, NULL, array('export' => $export));
	}

	/**
	 * Export Action for XLS Files
	 *
	 * @param array $export export settings
	 * @return void
	 */
	public function exportXlsBeAction(array $export = array()) {
		$mails = $this->mailRepository->findByUidList($export['mails'], $export['sorting']);
		$this->view->assign('mails', $mails);
		$this->view->assign('fieldUids', GeneralUtility::trimExplode(',', $export['fields'], 1));

		$fileName = ($this->settings['export']['filenameXls'] ? $this->settings['export']['filenameXls'] : 'export.xls');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: inline; filename="' . $fileName . '"');
		header('Pragma: no-cache');
	}

	/**
	 * Export Action for CSV Files
	 *
	 * @param array $export export settings
	 * @return void
	 */
	public function exportCsvBeAction(array $export = array()) {
		$mails = $this->mailRepository->findByUidList($export['mails'], $export['sorting']);
		$this->view->assign('mails', $mails);
		$this->view->assign('fieldUids', GeneralUtility::trimExplode(',', $export['fields'], 1));

		$fileName = ($this->settings['export']['filenameCsv'] ? $this->settings['export']['filenameCsv'] : 'export.csv');
		header('Content-Type: text/x-csv');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		header('Pragma: no-cache');
	}

	/**
	 * Object initialization
	 *
	 * @return void
	 */
	protected function initializeAction() {
		$this->piVars = $this->request->getArguments();
	}

}