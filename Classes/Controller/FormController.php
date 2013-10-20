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
 * Controller for powermail forms
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class Tx_Powermail_Controller_FormController extends Tx_Powermail_Controller_AbstractController {

	/**
	  * action show form for creating new mails
	  *
	  * @return void
	  */
	public function formAction() {
		if (!isset($this->settings['main']['form']) || !$this->settings['main']['form']) {
			return;
		}

		// get forms
		$forms = $this->formRepository->findByUids($this->settings['main']['form']);
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($forms, $this));
		$this->view->assign('forms', $forms);
		$this->view->assign('messageClass', $this->messageClass);
		$this->view->assign('action', ($this->settings['main']['confirmation'] ? 'confirmation' : 'create'));

		// open session
		if (method_exists($forms->getFirst(), 'getUid')) {
			Tx_Powermail_Utility_Div::saveFormStartInSession($forms->getFirst()->getUid());
		}
	}

	/**
	 * Rewrite Arguments to receive a clean mail object in createAction
	 *
	 * @return void
	 */
	public function initializeCreateAction() {
		$arguments = $this->request->getArguments();
		if (!isset($arguments['field'])) {
			return;
		}
		$newArguments = array(
			'mail' => $arguments['mail']
		);

		foreach ((array) $arguments['field'] as $marker => $value) {
			$newArguments['mail']['answers'][] = array(
				'field' => $this->div->getFieldUidFromMarker($marker, $arguments['mail']['form']),
				'value' => (is_array($value) && !empty($value['tmp_name']) ? $value['name'] : $value),
				'valueType' => Tx_Powermail_Utility_Div::getDataTypeFromFieldType(
					$this->div->getFieldTypeFromMarker($marker, $arguments['mail']['form'])
				)
			);
		}
		$this->request->setArguments($newArguments);
		$this->request->setArgument('field', NULL);
	}

	/**
	 * Action create entry
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail New mail object
	 * @return void
	 */
	public function createAction(Tx_Powermail_Domain_Model_Mail $mail = NULL) {
		// forward back to formAction if wrong form (only relevant if there are more powermail forms on one page)
		$this->ignoreWrongForm($mail);

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($mail, $this));

		// Debug Output
		if ($this->settings['debug']['variables']) {
			Tx_Extbase_Utility_Debugger::var_dump($mail);
		}

		// Save Mail to DB
		if ($this->settings['db']['enable']) { // todo don't save if optin
//			$dbField = $this->div->rewriteDateInFields($field); // todo check datepicker with optin
			$this->saveMail($mail);
			$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'AfterMailDbSaved', array($mail, $this));
		}

		if (!$this->settings['main']['optin'] || ($this->settings['main']['optin'] && $mail)) { // todo go in if you come from optin
			$this->sendMailPreflight($mail);

			// Save to other tables
//			$saveToTable = $this->objectManager->get('Tx_Powermail_Utility_SaveToTable');
//			$saveToTable->main($this->div->getVariablesWithMarkers($field), $this->conf, $this->cObj);

			// Powermail sendpost
//			$this->div->sendPost($field, $this->conf, $this->configurationManager);
		} else {
			$this->sendConfirmationMail($field, $newMail);
		}

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'AfterSubmitView', array($mail, $this));
		$this->view->assign('optinActive', (!$this->settings['main']['optin'] || ($this->settings['main']['optin'] && $mail) ? 0 : 1));

		$this->showThx($mail);
	}

	/**
	 * Show Confirmation message after submit (if view is activated)
	 *
	 * @param array $field Field values
	 * @param integer $form Form UID
	 * @validate $field Tx_Powermail_Domain_Validator_UploadValidator
	 * @validate $field Tx_Powermail_Domain_Validator_MandatoryValidator
	 * @validate $field Tx_Powermail_Domain_Validator_StringValidator
	 * @validate $field Tx_Powermail_Domain_Validator_CaptchaValidator
	 * @validate $field Tx_Powermail_Domain_Validator_SpamShieldValidator
	 * @validate $field Tx_Powermail_Domain_Validator_CustomValidator
	 * @return void
	 */
	public function confirmationAction(array $field = array(), $form = NULL) {
		// forward back to formAction if wrong form
		$this->ignoreWrongForm($form);

		Tx_Powermail_Utility_Div::addUploadsToFields($field); // add upload fields
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($field, $form, $this));
		$this->view->assign('field', $field);
		$this->view->assign('form', $form);

		// markers
		$variablesWithMarkers = $this->div->getVariablesWithMarkers($field);
		$this->view->assignMultiple($variablesWithMarkers);

		// powermail_all
		$variables = $this->div->getVariablesWithLabels($field);
		$content = $this->div->powermailAll($variables, $this->configurationManager, $this->objectManager, 'web', $this->settings);
		$this->view->assign('powermail_all', $content);
	}

	/**
	 * Choose where to send Mails
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	protected function sendMailPreflight(Tx_Powermail_Domain_Model_Mail $mail) {
		if ($this->settings['receiver']['enable']) {
			$this->sendReceiverMail($mail);
		}
//		if ($this->settings['sender']['enable'] && $this->div->getSenderMailFromArguments($field)) {
//			$this->sendSenderMail($mail);
//		}
	}

	/**
	 * Mail Generation for Receiver
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	protected function sendReceiverMail(Tx_Powermail_Domain_Model_Mail $mail) {
		$receiverString = $this->div->fluidParseString(
			$this->settings['receiver']['email'],
			$this->div->getVariablesWithMarkers($mail)
		);
		$this->div->overwriteValueFromTypoScript($receiverString, $this->conf['receiver.']['overwrite.'], 'email');
		$receivers = $this->div->getReceiverEmails(
			$receiverString,
			$this->settings['receiver']['fe_group']
		);
		foreach ($receivers as $receiver) {
			$email = array();
			$email['template'] = 'Mail/ReceiverMail';
			$email['receiverEmail'] = $receiver;
			$email['receiverName'] = $this->settings['receiver']['name'] ? $this->settings['receiver']['name'] : 'Powermail';
			$email['senderEmail'] = $this->div->getSenderMailFromArguments($mail);
			$email['senderName'] = $this->div->getSenderNameFromArguments($mail);
			$email['subject'] = $this->settings['receiver']['subject'];
			$email['rteBody'] = $this->settings['receiver']['body'];
			$email['format'] = $this->settings['receiver']['mailformat'];

			$this->div->overwriteValueFromTypoScript($email['receiverName'], $this->conf['receiver.']['overwrite.'], 'name');
			$this->div->overwriteValueFromTypoScript($email['senderName'], $this->conf['receiver.']['overwrite.'], 'senderName');
			$this->div->overwriteValueFromTypoScript($email['senderEmail'], $this->conf['receiver.']['overwrite.'], 'senderEmail');

			$sent = $this->div->sendTemplateEmail($email, $mail, $this->settings, 'receiver');

			if (!$sent) {
				$this->flashMessageContainer->add(
					Tx_Extbase_Utility_Localization::translate('error_mail_not_created',
						'powermail')
				);
				$this->messageClass = 'error';
			}
		}
	}

	/**
	 * Mail Generation for Sender
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	protected function sendSenderMail(Tx_Powermail_Domain_Model_Mail $mail) {
		// Send Mail to sender
		$mail = array();
		$mail['receiverName'] = $this->div->getSenderNameFromArguments($field) ? $this->div->getSenderNameFromArguments($field) : 'Powermail';
		$mail['receiverEmail'] = $this->div->getSenderMailFromArguments($field);
		$mail['senderName'] = $this->settings['sender']['name'];
		$mail['senderEmail'] = $this->settings['sender']['email'];
		$mail['subject'] = $this->settings['sender']['subject'];
		$mail['template'] = 'Mail/SenderMail';
		$mail['rteBody'] = $this->settings['sender']['body'];
		$mail['format'] = $this->settings['sender']['mailformat'];
		if ($this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['email'], $this->conf['sender.']['overwrite.']['email.'])) { // overwrite from typoscript
			$mail['receiverEmail'] = $this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['email'], $this->conf['sender.']['overwrite.']['email.']);
		}
		if ($this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['name'], $this->conf['sender.']['overwrite.']['name.'])) { // overwrite from typoscript
			$mail['receiverName'] = $this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['name'], $this->conf['sender.']['overwrite.']['name.']);
		}
		if ($this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['senderName'], $this->conf['sender.']['overwrite.']['senderName.'])) { // overwrite from typoscript
			$mail['senderName'] = $this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['senderName'], $this->conf['sender.']['overwrite.']['senderName.']);
		}
		if ($this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['senderEmail'], $this->conf['sender.']['overwrite.']['senderEmail.'])) { // overwrite from typoscript
			$mail['senderEmail'] = $this->cObj->cObjGetSingle($this->conf['sender.']['overwrite.']['senderEmail'], $this->conf['sender.']['overwrite.']['senderEmail.']);
		}
		$this->div->sendTemplateEmail($mail, $field, $this->settings, 'sender', $this->objectManager, $this->configurationManager);
	}

	/**
	 * Send Optin Confirmation Mail
	 *
	 * @param array $field array with field values
	 * @param Tx_Powermail_Domain_Model_Mail $newMail new mail object from db
	 * @return void
	 */
	protected function sendConfirmationMail($field, $newMail) {
		// Send Mail to sender
		$mail = array();
		$mail['receiverName'] = 'Powermail';
		if ($this->div->getSenderNameFromArguments($field)) {
			$mail['receiverName'] = $this->div->getSenderNameFromArguments($field);
		}
		if ($this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['name'], $this->conf['optin.']['overwrite.']['name.'])) { // overwrite from typoscript
			$mail['receiverName'] = $this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['name'], $this->conf['optin.']['overwrite.']['name.']);
		}
		$mail['receiverEmail'] = $this->div->getSenderMailFromArguments($field);
		if ($this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['email'], $this->conf['optin.']['overwrite.']['email.'])) { // overwrite from typoscript
			$mail['receiverEmail'] = $this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['email'], $this->conf['optin.']['overwrite.']['email.']);
		}
		$mail['senderName'] = $this->settings['sender']['name'];
		if ($this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['senderName'], $this->conf['optin.']['overwrite.']['senderName.'])) { // overwrite from typoscript
			$mail['senderName'] = $this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['senderName'], $this->conf['optin.']['overwrite.']['senderName.']);
		}
		$mail['senderEmail'] = $this->settings['sender']['email'];
		if ($this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['senderEmail'], $this->conf['optin.']['overwrite.']['senderEmail.'])) { // overwrite from typoscript
			$mail['senderEmail'] = $this->cObj->cObjGetSingle($this->conf['optin.']['overwrite.']['senderEmail'], $this->conf['optin.']['overwrite.']['senderEmail.']);
		}
		$mail['subject'] = $this->cObj->cObjGetSingle($this->conf['optin.']['subject'], $this->conf['optin.']['subject.']);
		$mail['template'] = 'Mail/OptinMail';
		$mail['rteBody'] = '';
		$mail['format'] = $this->settings['sender']['mailformat'];
		$mail['variables'] = array(
			'optinHash' => Tx_Powermail_Utility_Div::createOptinHash($newMail->getUid() . $newMail->getPid() . $newMail->getForm()),
			'mail' => $newMail->getUid()
		);
		$this->div->sendTemplateEmail($mail, $field, $this->settings, 'optin', $this->objectManager, $this->configurationManager);
	}

	/**
	 * Show THX message after submit
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	protected function showThx(Tx_Powermail_Domain_Model_Mail $mail) {
		$this->redirectToTarget();

		// assign
		$this->view->assign('mail', $mail);
		$this->view->assign('marketingInfos', Tx_Powermail_Utility_Div::getMarketingInfos());
		$this->view->assign('messageClass', $this->messageClass);
		$this->view->assign('powermail_rte', $this->settings['thx']['body']);

		// get variable array
		$variablesWithMarkers = $this->div->getVariablesWithMarkers($mail);
		$this->view->assign('variablesWithMarkers', $this->div->htmlspecialcharsOnArray($variablesWithMarkers));
		$this->view->assignMultiple($variablesWithMarkers);

		// powermail_all
		$content = $this->div->powermailAll($mail, $this->configurationManager, $this->objectManager, 'web', $this->settings);
		$this->view->assign('powermail_all', $content);
	}

	/**
	 * Redirect on thx action
	 *
	 * @return void
	 */
	protected function redirectToTarget() {
		$target = NULL;

		// redirect from flexform
		if (!empty($this->settings['thx']['redirect'])) {
			$target = $this->settings['thx']['redirect'];
		}

		// redirect from TypoScript cObject
		if ($this->cObj->cObjGetSingle($this->conf['thx.']['overwrite.']['redirect'], $this->conf['thx.']['overwrite.']['redirect.'])) {
			$target = $this->cObj->cObjGetSingle($this->conf['thx.']['overwrite.']['redirect'], $this->conf['thx.']['overwrite.']['redirect.']);
		}

		// if redirect target
		if ($target) {
			$this->uriBuilder->setTargetPageUid($target);
			$link = $this->uriBuilder->build();
			$this->redirectToUri($link);
		}
		return;
	}

	/**
	 * Save mail on submit
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail = NULL
	 * @return void
	 */
	protected function saveMail(Tx_Powermail_Domain_Model_Mail &$mail = NULL) {
		$marketingInfos = Tx_Powermail_Utility_Div::getMarketingInfos();
		$mail->setPid(
			Tx_Powermail_Utility_Div::getStoragePage($this->settings['main']['pid'])
		);
		$mail->setSenderMail($this->div->getSenderMailFromArguments($mail));
		$mail->setSenderName($this->div->getSenderNameFromArguments($mail));
		$mail->setSubject($this->settings['receiver']['subject']);
		$mail->setReceiverMail($this->settings['receiver']['email']);
		$mail->setBody(
			t3lib_utility_Debug::viewArray(
				$this->div->getVariablesWithLabels($mail)
			)
		);
		$mail->setSpamFactor($GLOBALS['TSFE']->fe_user->getKey('ses', 'powermail_spamfactor'));
		$mail->setTime((time() - Tx_Powermail_Utility_Div::getFormStartFromSession($mail->getForm()->getUid())));
		$mail->setUserAgent(t3lib_div::getIndpEnv('HTTP_USER_AGENT'));
		$mail->setMarketingSearchterm($marketingInfos['marketingSearchterm']);
		$mail->setMarketingReferer($marketingInfos['marketingReferer']);
		$mail->setMarketingPayedSearchResult($marketingInfos['marketingPayedSearchResult']);
		$mail->setMarketingLanguage($marketingInfos['marketingLanguage']);
		$mail->setMarketingBrowserLanguage($marketingInfos['marketingBrowserLanguage']);
		$mail->setMarketingFunnel($marketingInfos['marketingFunnel']);
		if (intval($GLOBALS['TSFE']->fe_user->user['uid']) > 0) {
			$mail->setFeuser($GLOBALS['TSFE']->fe_user->user['uid']);
		}
		if (isset($this->settings['global']['disableIpLog']) && $this->settings['global']['disableIpLog'] == 0) {
			$mail->setSenderIp(t3lib_div::getIndpEnv('REMOTE_ADDR'));
		}
		if ($this->settings['main']['optin'] || $this->settings['db']['hidden']) {
			$mail->setHidden(1);
		}
		foreach ($mail->getAnswers() as $answer) {
			$answer->setPid(
				Tx_Powermail_Utility_Div::getStoragePage($this->settings['main']['pid'])
			);
		}
		$this->mailRepository->add($mail);
		$persistenceManager = $this->objectManager->get('Tx_Extbase_Persistence_Manager');
		$persistenceManager->persistAll();
	}

	/**
	 * Confirm Double Optin
	 *
	 * @param int $mail Mail Uid
	 * @param string $hash Given Hash String
	 * @dontvalidate $mail
	 * @dontvalidate $hash
	 * return void
	 */
	public function optinConfirmAction($mail = NULL, $hash = NULL) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($mail, $hash, $this));
		$mail = $this->mailRepository->findByUid($mail);

		if (
			!empty($hash) &&
			$mail instanceof Tx_Powermail_Domain_Model_Mail &&
			$hash == Tx_Powermail_Utility_Div::createOptinHash($mail->getUid() . $mail->getPid() . $mail->getForm()->getUid())
		) {
			// only if hidden = 0
			if ($mail->getHidden() == 1) {
				$mail->setHidden(0);

				$this->mailRepository->update($mail);
				$persistenceManager = $this->objectManager->get('Tx_Extbase_Persistence_Manager');
				$persistenceManager->persistAll();

				// call create action
				$fields = array();
				foreach ($mail->getAnswers() as $answer) {
					$fields[$answer->getField()] = $answer->getValue();
				}
				$arguments = array(
					'field' => $fields,
					'form' => $mail->getForm()->getUid(),
					'mail' => $mail->getUid(),
					'__referrer' => array(
						'actionName' => 'optinConfirm'
					)
				);
				$_POST['tx_powermail_pi1']['__referrer']['actionName'] = 'optinConfirm'; // workarround to set the referrer and call it again in the validator
				$this->forward('create', NULL, NULL, $arguments);
			}
		}
	}

	/**
	 * Initializes this object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->cObj = $this->configurationManager->getContentObject();
		$typoScriptSetup = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$this->conf = $typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.'];

		Tx_Powermail_Utility_Div::mergeTypoScript2FlexForm($this->settings); // merge typoscript to flexform (if flexform field also exists and is empty, take typoscript part)
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'Settings', array($this));

		// check if ts is included
		if (!isset($this->settings['staticTemplate'])) {
			$this->flashMessageContainer->add(Tx_Extbase_Utility_Localization::translate('error_no_typoscript', 'powermail'));
		}

		// Debug Output
		if ($this->settings['debug']['settings']) {
			t3lib_utility_Debug::debug($this->settings, 'powermail debug: Show Settings');
		}
	}

	/**
	 * Forward to form action if wrong form in plugin variables
	 *
	 * @param Tx_Powermail_Domain_Model_Mail $mail
	 * @return void
	 */
	protected function ignoreWrongForm(Tx_Powermail_Domain_Model_Mail $mail) {
		$pluginHasThisAssignedForms = t3lib_div::intExplode(',', $this->settings['main']['form']);
		if (!in_array($mail->getForm()->getUid(), $pluginHasThisAssignedForms)) {
			$this->forward('form');
		}
	}

}