<?php
namespace In2code\Powermail\Controller;

use \In2code\Powermail\Utility\BasicFileFunctions,
	\In2code\Powermail\Utility\Div,
	\In2code\Powermail\Domain\Model\Mail,
	\TYPO3\CMS\Core\Utility\GeneralUtility,
	\TYPO3\CMS\Extbase\Utility\LocalizationUtility;

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
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class FormController extends \In2code\Powermail\Controller\AbstractController {

	/**
	 * @var \In2code\Powermail\Utility\SendMail
	 * @inject
	 */
	protected $sendMail;

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
		$this->assignForAll();

		// create session
		if (method_exists($forms->getFirst(), 'getUid')) {
			Div::saveFormStartInSession($forms->getFirst()->getUid());
		}
	}

	/**
	 * Rewrite Arguments to receive a clean mail object in createAction
	 *
	 * @return void
	 */
	public function initializeCreateAction() {
		$this->reformatParamsForAction();
	}

	/**
	 * Action create entry
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param \string $hash
	 * @validate $mail In2code\Powermail\Domain\Validator\UploadValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\InputValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\CaptchaValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\SpamShieldValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\CustomValidator
	 * @required $mail
	 * @return void
	 */
	public function createAction(Mail $mail, $hash = NULL) {
		$this->ignoreWrongForm($mail);
		BasicFileFunctions::fileUpload($this->settings['misc']['file']['folder'], $this->settings['misc']['file']['extension'], $mail);

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($mail, $hash, $this));

		// Debug Output
		if ($this->settings['debug']['variables']) {
			GeneralUtility::devLog(
				'Variables',
				$this->extensionName,
				0,
				$_REQUEST
			);
		}

		// Save Mail to DB
		if ($this->settings['db']['enable'] && $hash === NULL) {
			$this->saveMail($mail);
			$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'AfterMailDbSaved', array($mail, $this));
		}

		// If no optin, send mail
		if (
			!$this->settings['main']['optin'] ||
			($this->settings['main']['optin'] && Div::checkOptinHash($hash, $mail) && $hash !== NULL)
		) {
			$this->sendMailPreflight($mail);

			// Save to other tables if activated
			$this->div->saveToAnyTable($mail, $this->conf);

			// Send values to a third party software (like a CRM)
			$this->div->sendPost($mail, $this->conf);

		} else {
			$this->sendConfirmationMail($mail);
			$this->view->assign('optinActive', TRUE);
		}

		// update mail with parsed fields from TS (subject, etc...)
		if ($this->settings['db']['enable']) {
			$this->mailRepository->update($mail);
			$this->persistenceManager->persistAll();
		}

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'AfterSubmitView', array($mail, $hash, $this));
		$this->assignForAll();
		$this->showThx($mail);
	}

	/**
	 * Rewrite Arguments to receive a clean mail object in confirmationAction
	 *
	 * @return void
	 */
	public function initializeConfirmationAction() {
		$this->reformatParamsForAction();
	}

	/**
	 * Show Confirmation message after submit (if view is activated)
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @validate $mail In2code\Powermail\Domain\Validator\UploadValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\InputValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\CaptchaValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\SpamShieldValidator
	 * @validate $mail In2code\Powermail\Domain\Validator\CustomValidator
	 * @required $mail
	 * @return void
	 */
	public function confirmationAction(Mail $mail) {
		$this->ignoreWrongForm($mail);
		BasicFileFunctions::fileUpload($this->settings['misc']['file']['folder'], $this->settings['misc']['file']['extension'], $mail);

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($mail, $this));

		$this->showThx($mail);
	}

	/**
	 * Choose where to send Mails
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function sendMailPreflight(Mail $mail) {
		if ($this->settings['receiver']['enable']) {
			$this->sendReceiverMail($mail);
		}
		if ($this->settings['sender']['enable'] && $this->div->getSenderMailFromArguments($mail)) {
			$this->sendSenderMail($mail);
		}
	}

	/**
	 * Mail Generation for Receiver
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function sendReceiverMail(Mail $mail) {
		$receiverString = $this->div->fluidParseString(
			$this->settings['receiver']['email'],
			$this->div->getVariablesWithMarkers($mail)
		);
		$this->div->overwriteValueFromTypoScript($receiverString, $this->conf['receiver.']['overwrite.'], 'email');
		$receivers = $this->div->getReceiverEmails(
			$receiverString,
			$this->settings['receiver']['fe_group']
		);
		$mail->setReceiverMail(implode("\n", $receivers));
		foreach ($receivers as $receiver) {
			$email = array(
				'template' => 'Mail/ReceiverMail',
				'receiverEmail' => $receiver,
				'receiverName' => $this->settings['receiver']['name'] ? $this->settings['receiver']['name'] : 'Powermail',
				'senderEmail' => $this->div->getSenderMailFromArguments($mail),
				'senderName' => $this->div->getSenderNameFromArguments($mail),
				'subject' => $this->settings['receiver']['subject'],
				'rteBody' => $this->settings['receiver']['body'],
				'format' => $this->settings['receiver']['mailformat']
			);
			$this->div->overwriteValueFromTypoScript($email['receiverName'], $this->conf['receiver.']['overwrite.'], 'name');
			$this->div->overwriteValueFromTypoScript($email['senderName'], $this->conf['receiver.']['overwrite.'], 'senderName');
			$this->div->overwriteValueFromTypoScript($email['senderEmail'], $this->conf['receiver.']['overwrite.'], 'senderEmail');
			$sent = $this->sendMail->sendTemplateEmail($email, $mail, $this->settings, 'receiver');

			if (!$sent) {
				$this->addFlashMessage(LocalizationUtility::translate('error_mail_not_created', 'powermail'));
				$this->messageClass = 'error';
			}
		}
	}

	/**
	 * Mail Generation for Sender
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function sendSenderMail(Mail $mail) {
		// Send Mail to sender
		$email = array(
			'template' => 'Mail/SenderMail',
			'receiverName' => $this->div->getSenderNameFromArguments($mail) ? $this->div->getSenderNameFromArguments($mail) : 'Powermail',
			'receiverEmail' => $this->div->getSenderMailFromArguments($mail),
			'senderName' => $this->settings['sender']['name'],
			'senderEmail' => $this->settings['sender']['email'],
			'subject' => $this->settings['sender']['subject'],
			'rteBody' => $this->settings['sender']['body'],
			'format' => $this->settings['sender']['mailformat']
		);
		$this->div->overwriteValueFromTypoScript($email['receiverEmail'], $this->conf['sender.']['overwrite.'], 'email');
		$this->div->overwriteValueFromTypoScript($email['receiverName'], $this->conf['sender.']['overwrite.'], 'name');
		$this->div->overwriteValueFromTypoScript($email['senderName'], $this->conf['sender.']['overwrite.'], 'senderName');
		$this->div->overwriteValueFromTypoScript($email['senderEmail'], $this->conf['sender.']['overwrite.'], 'senderEmail');
		$this->sendMail->sendTemplateEmail($email, $mail, $this->settings, 'sender');
	}

	/**
	 * Send Optin Confirmation Mail to user
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function sendConfirmationMail(Mail &$mail) {
		// Send Mail to sender with hashed link
		$email = array(
			'template' => 'Mail/OptinMail',
			'receiverName' => $this->div->getSenderNameFromArguments($mail) ? $this->div->getSenderNameFromArguments($mail) : 'Powermail',
			'receiverEmail' => $this->div->getSenderMailFromArguments($mail),
			'senderName' => $this->settings['sender']['name'],
			'senderEmail' => $this->settings['sender']['email'],
			'subject' => $this->cObj->cObjGetSingle($this->conf['optin.']['subject'], $this->conf['optin.']['subject.']),
			'rteBody' => '',
			'format' => $this->settings['sender']['mailformat'],
			'variables' => array(
				'hash' => Div::createOptinHash($mail),
				'mail' => $mail
			)
		);
		$this->div->overwriteValueFromTypoScript($email['receiverName'], $this->conf['optin.']['overwrite.'], 'name');
		$this->div->overwriteValueFromTypoScript($email['receiverEmail'], $this->conf['optin.']['overwrite.'], 'email');
		$this->div->overwriteValueFromTypoScript($email['senderName'], $this->conf['optin.']['overwrite.'], 'senderName');
		$this->div->overwriteValueFromTypoScript($email['senderEmail'], $this->conf['optin.']['overwrite.'], 'senderEmail');
		$this->sendMail->sendTemplateEmail($email, $mail, $this->settings, 'optin');
	}

	/**
	 * Show THX message after submit
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function showThx(Mail $mail) {
		$this->redirectToTarget();

		// assign
		$this->view->assign('mail', $mail);
		$this->view->assign('marketingInfos', Div::getMarketingInfos());
		$this->view->assign('messageClass', $this->messageClass);
		$this->view->assign('powermail_rte', $this->settings['thx']['body']);

		// get variable array
		$variablesWithMarkers = $this->div->getVariablesWithMarkers($mail);
		$this->view->assign('variablesWithMarkers', $this->div->htmlspecialcharsOnArray($variablesWithMarkers));
		$this->view->assignMultiple($variablesWithMarkers);

		// powermail_all
		$content = $this->div->powermailAll($mail, 'web', $this->settings);
		$this->view->assign('powermail_all', $content);
	}

	/**
	 * Redirect on thx action
	 *
	 * @return void
	 */
	protected function redirectToTarget() {
		if ($this->request->getControllerActionName() === 'confirmation') {
			return;
		}
		$target = NULL;

		// redirect from flexform
		if (!empty($this->settings['thx']['redirect'])) {
			$target = $this->settings['thx']['redirect'];
		}

		// redirect from TypoScript cObject
		$targetFromTypoScript = $this->cObj->cObjGetSingle(
			$this->conf['thx.']['overwrite.']['redirect'],
			$this->conf['thx.']['overwrite.']['redirect.']
		);
		if (!empty($targetFromTypoScript)) {
			$target = $targetFromTypoScript;
		}

		// if redirect target
		if ($target) {
			$this->uriBuilder->setTargetPageUid($target);
			$link = $this->uriBuilder->build();
			$this->redirectToUri($link);
		}
	}

	/**
	 * Save mail on submit
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function saveMail(Mail &$mail = NULL) {
		$marketingInfos = Div::getMarketingInfos();
		$mail->setPid(
			Div::getStoragePage($this->settings['main']['pid'])
		);
		$mail->setSenderMail($this->div->getSenderMailFromArguments($mail));
		$mail->setSenderName($this->div->getSenderNameFromArguments($mail));
		$mail->setSubject($this->settings['receiver']['subject']);
		$mail->setReceiverMail($this->settings['receiver']['email']);
		$mail->setBody(
			\TYPO3\CMS\Core\Utility\DebugUtility::viewArray(
				$this->div->getVariablesWithLabels($mail)
			)
		);
		$mail->setSpamFactor($GLOBALS['TSFE']->fe_user->getKey('ses', 'powermail_spamfactor'));
		$mail->setTime((time() - Div::getFormStartFromSession($mail->getForm()->getUid())));
		$mail->setUserAgent(GeneralUtility::getIndpEnv('HTTP_USER_AGENT'));
		$mail->setMarketingRefererDomain($marketingInfos['refererDomain']);
		$mail->setMarketingReferer($marketingInfos['referer']);
		$mail->setMarketingCountry($marketingInfos['country']);
		$mail->setMarketingMobileDevice($marketingInfos['mobileDevice']);
		$mail->setMarketingFrontendLanguage($marketingInfos['frontendLanguage']);
		$mail->setMarketingBrowserLanguage($marketingInfos['browserLanguage']);
		$mail->setMarketingPageFunnel($marketingInfos['pageFunnel']);
		if (intval($GLOBALS['TSFE']->fe_user->user['uid']) > 0) {
			$mail->setFeuser(
				$this->userRepository->findByUid(
					Div::getPropertyFromLoggedInFeUser('uid')
				)
			);
		}
		if (empty($this->settings['global']['disableIpLog'])) {
			$mail->setSenderIp(
				GeneralUtility::getIndpEnv('REMOTE_ADDR')
			);
		}
		if ($this->settings['main']['optin'] || $this->settings['db']['hidden']) {
			$mail->setHidden(TRUE);
		}
		foreach ($mail->getAnswers() as $answer) {
			$answer->setPid(
				Div::getStoragePage($this->settings['main']['pid'])
			);
		}
		$this->mailRepository->add($mail);
		$this->persistenceManager->persistAll();
	}

	/**
	 * Confirm Double Optin
	 *
	 * @param \int $mail
	 * @param string $hash Given Hash String
	 * @return void
	 */
	public function optinConfirmAction($mail, $hash) {
		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'BeforeRenderView', array($mail, $hash, $this));
		$mail = $this->mailRepository->findByUid($mail);

		if (Div::checkOptinHash($hash, $mail)) {
			if ($mail->getHidden()) {
				$mail->setHidden(FALSE);
				$this->mailRepository->update($mail);
				$this->persistenceManager->persistAll();

				$this->forward('create', NULL, NULL, array('mail' => $mail, 'hash' => $hash));
			}
		}
	}

	/**
	 * Marketing Tracking Action
	 *
	 * @param \string $referer Referer
	 * @param \int $language Frontend Language Uid
	 * @param \int $pid Page Id
	 * @param \int $mobileDevice Is mobile device?
	 * @return void
	 */
	public function marketingAction($referer = NULL, $language = 0, $pid = 0, $mobileDevice = 0) {
		Div::storeMarketingInformation($referer, $language, $pid, $mobileDevice);
	}

	/**
	 * Initializes this object
	 *
	 * @return void
	 */
	public function initializeObject() {
		$this->cObj = $this->configurationManager->getContentObject();
		$typoScriptSetup = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
		);
		$this->conf = $typoScriptSetup['plugin.']['tx_powermail.']['settings.']['setup.'];

		// merge extension manager settings and typoscript and flexform
		Div::mergeTypoScript2FlexForm($this->settings);

		$this->signalSlotDispatcher->dispatch(__CLASS__, __FUNCTION__ . 'Settings', array($this));

		// Debug Output
		if ($this->settings['debug']['settings']) {
			GeneralUtility::devLog(
				'Settings',
				$this->extensionName,
				0,
				$this->settings
			);
		}
	}

	/**
	 * Initialize Action
	 *
	 * @return void
	 */
	public function initializeAction() {
		if (!isset($this->settings['staticTemplate'])) {
			$this->controllerContext = $this->buildControllerContext();
			$this->addFlashMessage(LocalizationUtility::translate('error_no_typoscript', 'powermail'));
		}
	}

	/**
	 * Forward to form action if wrong form in plugin variables
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	protected function ignoreWrongForm(Mail $mail) {
		$pluginHasThisAssignedForms = GeneralUtility::intExplode(',', $this->settings['main']['form']);
		if (!in_array($mail->getForm()->getUid(), $pluginHasThisAssignedForms)) {
			$this->forward('form');
		}
	}

}