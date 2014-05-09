<?php
namespace In2code\Powermail\Utility;

use \TYPO3\CMS\Core\Utility\GeneralUtility,
	\In2code\Powermail\Utility\Div;

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
 * Main Send Mail Class
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class SendMail {

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 * @inject
	 */
	protected $objectManager;

	/**
	 * SignalSlot Dispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * mailRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\MailRepository
	 * @inject
	 */
	protected $mailRepository;

	/**
	 * @var \In2code\Powermail\Utility\Div
	 * @inject
	 */
	protected $div;

	/**
	 * This is the main-function for sending Mails
	 *
	 * @param array $email Array with all needed mail information
	 * 		$email['receiverName'] = 'Name';
	 * 		$email['receiverEmail'] = 'receiver@mail.com';
	 * 		$email['senderName'] = 'Name';
	 * 		$email['senderEmail'] = 'sender@mail.com';
	 * 		$email['subject'] = 'Subject line';
	 * 		$email['template'] = 'PathToTemplate/';
	 * 		$email['rteBody'] = 'This is the <b>content</b> of the RTE';
	 * 		$email['format'] = 'both'; // or plain or html
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param array $settings TypoScript Settings
	 * @param string $type Email to "sender" or "receiver"
	 * @return bool Mail successfully sent
	 */
	public function sendTemplateEmail(array $email, \In2code\Powermail\Domain\Model\Mail &$mail, $settings, $type = 'receiver') {
		$cObj = $this->configurationManager->getContentObject();
		$typoScriptService = $this->objectManager->get('\TYPO3\CMS\Extbase\Service\TypoScriptService');
		$conf = $typoScriptService->convertPlainArrayToTypoScriptArray($settings);

		// parsing variables with fluid engine to allow viewhelpers in flexform
		$parse = array(
			'receiverName',
			'receiverEmail',
			'senderName',
			'senderEmail',
			'subject'
		);
		foreach ($parse as $value) {
			$email[$value] = $this->div->fluidParseString($email[$value], $this->div->getVariablesWithMarkers($mail));
		}

		// Debug Output
		if ($settings['debug']['mail']) {
			GeneralUtility::devLog(
				'Mail properties',
				'powermail',
				0,
				$email
			);
		}

		// stop mail process if receiver or sender email is not valid
		if (!GeneralUtility::validEmail($email['receiverEmail']) || !GeneralUtility::validEmail($email['senderEmail'])) {
			return FALSE;
		}

		// stop mail process if subject is empty
		if (empty($email['subject'])) {
			return FALSE;
		}

		$message = GeneralUtility::makeInstance('\TYPO3\CMS\Core\Mail\MailMessage');
		$this->div->overwriteValueFromTypoScript($email['subject'], $conf[$type . '.']['overwrite.'], 'subject');
		$message
			->setTo(array($email['receiverEmail'] => $email['receiverName']))
			->setFrom(array($email['senderEmail'] => $email['senderName']))
			->setSubject($email['subject'])
			->setCharset($GLOBALS['TSFE']->metaCharset);

		// add cc receivers
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['cc'], $conf[$type . '.']['overwrite.']['cc.'])) {
			$ccArray = GeneralUtility::trimExplode(
				',',
				$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['cc'],
					$conf[$type . '.']['overwrite.']['cc.']),
				1
			);
			$message->setCc($ccArray);
		}

		// add bcc receivers
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['bcc'], $conf[$type . '.']['overwrite.']['bcc.'])) {
			$bccArray = GeneralUtility::trimExplode(
				',',
				$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['bcc'],
					$conf[$type . '.']['overwrite.']['bcc.']),
				1
			);
			$message->setBcc($bccArray);
		}

		// add Return Path
		if ($cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['returnPath'], $conf[$type . '.']['overwrite.']['returnPath.'])) {
			$message->setReturnPath(
				$cObj->cObjGetSingle($conf[$type . '.']['overwrite.']['returnPath'],
					$conf[$type . '.']['overwrite.']['returnPath.'])
			);
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
			foreach ($mail->getAnswers() as $answer) {
				$values = $answer->getValue();
				if ($answer->getValueType() === 3 && is_array($values) && !empty($values)) {
					foreach ($values as $value) {
						$file = $settings['misc']['file']['folder'] . $value;
						if (file_exists(GeneralUtility::getFileAbsFileName($file))) {
							$message->attach(\Swift_Attachment::fromPath($file));
						} else {
							GeneralUtility::devLog('Error: File to attach does not exist', 'powermail', 2, $file);
						}
					}
				}
			}
		}

		// add attachments from TypoScript
		if ($cObj->cObjGetSingle($conf[$type . '.']['addAttachment'], $conf[$type . '.']['addAttachment.'])) {
			$files = GeneralUtility::trimExplode(
				',',
				$cObj->cObjGetSingle($conf[$type . '.']['addAttachment'], $conf[$type . '.']['addAttachment.']),
				1
			);
			foreach ($files as $file) {
				if (file_exists(GeneralUtility::getFileAbsFileName($file))) {
					$message->attach(\Swift_Attachment::fromPath($file));
				} else {
					GeneralUtility::devLog('Error: File to attach does not exist', 'powermail', 2, $file);
				}
			}
		}
		if ($email['format'] != 'plain') {
			$message->setBody(
				$this->createEmailBody($email, $mail, $settings),
				'text/html'
			);
		}
		if ($email['format'] != 'html') {
			$message->addPart(
				$this->makePlain($this->createEmailBody($email, $mail, $settings)),
				'text/plain'
			);
		}
		$message->send();

		// update mail
		$mail->setSenderMail($email['senderEmail']);
		$mail->setSenderName($email['senderName']);
		$mail->setSubject($email['subject']);

		return $message->isSent();
	}

	/**
	 * Create Email Body
	 *
	 * @param array $email Array with all needed mail information
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @param array $settings TypoScript Settings
	 * @return bool
	 */
	protected function createEmailBody($email, \In2code\Powermail\Domain\Model\Mail &$mail, $settings) {
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(
			\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK
		);
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
		$templatePathAndFilename .= $email['template'] . '.html';
		$emailBodyObject = $this->objectManager->get('\TYPO3\CMS\Fluid\View\StandaloneView');
		$emailBodyObject->getRequest()->setControllerExtensionName('Powermail');
		$emailBodyObject->getRequest()->setPluginName('Pi1');
		$emailBodyObject->getRequest()->setControllerName('Form');
		$emailBodyObject->setFormat('html');
		$emailBodyObject->setTemplatePathAndFilename($templatePathAndFilename);
		$emailBodyObject->setPartialRootPath(
			GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['partialRootPath'])
		);
		$emailBodyObject->setLayoutRootPath(
			GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['layoutRootPath'])
		);

		// get variables
		// additional variables
		if (isset($email['variables']) && is_array($email['variables'])) {
			$emailBodyObject->assignMultiple($email['variables']);
		}
		// markers in HTML Template
		$variablesWithMarkers = $this->div->getVariablesWithMarkers($mail);
		$emailBodyObject->assign('variablesWithMarkers', $this->div->htmlspecialcharsOnArray($variablesWithMarkers));
		$emailBodyObject->assignMultiple($variablesWithMarkers);
		$emailBodyObject->assign('powermail_all', $this->div->powermailAll($mail, 'mail', $settings));
		// from rte
		$emailBodyObject->assign('powermail_rte', $email['rteBody']);
		$emailBodyObject->assign('marketingInfos', Div::getMarketingInfos());
		$emailBodyObject->assign('mail', $mail);
		$body = $emailBodyObject->render();
		$mail->setBody($body);
		return $body;
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

		// This tags will be added with linebreaks
		$htmltagarray = array (
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
		// This array contains not allowed signs which will be removed
		$notallowed = array (
			'&nbsp;',
			'&szlig;',
			'&Uuml;',
			'&uuml;',
			'&Ouml;',
			'&ouml;',
			'&Auml;',
			'&auml;',
		);

		$content = nl2br($content);
		// 1. add linebreaks on some parts (</p> => </p><br />)
		$content = str_replace($htmltagarray, $htmltagarray[0] . '<br />', $content);
		// 2. remove all tags (<b>bla</b><br /> => bla<br />)
		$content = strip_tags($content, '<br><address>');
		// 3. removes tabs and whitespaces
		$content = preg_replace('/\s+/', ' ', $content);
		// 4. <br /> to \n
		$content = $this->br2nl($content);
		// 5. explode and trim and implode again (" bla \n blabla " => "bla\nbla")
		$content = implode("\n", GeneralUtility::trimExplode("\n", $content));
		// 6. remove not allowed signs
		$content = str_replace($notallowed, '', $content);

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
		// replacer
		$content = str_replace($array, "\n", $content);

		return $content;
	}
}