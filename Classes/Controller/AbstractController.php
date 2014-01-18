<?php
namespace In2code\Powermail\Controller;

use \In2code\Powermail\Utility\Div;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \In2code\Powermail\Domain\Model\Mail;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Alex Kellner <alexander.kellner@in2code.de>, in2code.de
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
 * Abstract Controller for powermail
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * formRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\FormRepository
	 * @inject
	 */
	protected $formRepository;

	/**
	 * mailRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\MailRepository
	 * @inject
	 */
	protected $mailRepository;

	/**
	 * answerRepository
	 *
	 * @var \In2code\Powermail\Domain\Repository\AnswerRepository
	 * @inject
	 */
	protected $answerRepository;

	/**
	 * SignalSlot Dispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 * @inject
	 */
	protected $signalSlotDispatcher;

	/**
	 * SignalSlot Dispatcher
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * Instance for Misc Functions
	 *
	 * @var \In2code\Powermail\Utility\Div
	 * @inject
	 */
	protected $div;

	/**
	 * cObj
	 *
	 * @var tslib_cObj
	 */
	protected $cObj;

	/**
	 * TypoScript configuration
	 *
	 * @var array
	 */
	protected $conf;

	/**
	 * message Class
	 *
	 * @var string
	 */
	protected $messageClass = 'error';

	/**
	 * Reformat Array
	 *
	 * @return void
	 */
	public function initializeValidateAjaxAction() {
		$this->reformatParamsForAction();
	}

	/**
	 * Validate field
	 *
	 * @param \In2code\Powermail\Domain\Model\Mail $mail
	 * @return void
	 */
	public function validateAjaxAction(Mail $mail) {
		$pluginVariables = GeneralUtility::_GET('tx_powermail_pi1');

		// get value
		$value = array_shift($pluginVariables['field']);

		$inputValidator = $this->objectManager->get('\In2code\Powermail\Domain\Validator\InputValidator');
		$isValid = $inputValidator->isValid($mail, $value);

		$this->view->assign('isValid', $isValid);
		$this->view->assign('errors', $inputValidator->getErrors());
	}

	/**
	 * Reformat array for createAction
	 *
	 * @return void
	 */
	protected function reformatParamsForAction() {
		$arguments = $this->request->getArguments();
		if (!isset($arguments['field'])) {
			return;
		}
		$newArguments = array(
			'mail' => $arguments['mail']
		);

		// allow subvalues in new property mapper
		$this->arguments['mail']->getPropertyMappingConfiguration()->allowCreationForSubProperty('answers');
		$this->arguments['mail']->getPropertyMappingConfiguration()->allowModificationForSubProperty('answers');

		$i = 0;
		foreach ((array) $arguments['field'] as $marker => $value) {
			// ignore internal fields (honeypod)
			if (substr($marker, 0, 2) === '__') {
				continue;
			}

			// allow subvalues in new property mapper
			$this->arguments['mail']->getPropertyMappingConfiguration()->allowCreationForSubProperty('answers.' . $i);
			$this->arguments['mail']->getPropertyMappingConfiguration()->allowModificationForSubProperty('answers.' . $i);

			$newArguments['mail']['answers'][$i] = array(
				'field' => strval($this->div->getFieldUidFromMarker($marker, $arguments['mail']['form'])),
				'value' => (is_array($value) && !empty($value['tmp_name']) ? $value['name'] : $value),
				'valueType' => Div::getDataTypeFromFieldType(
					$this->div->getFieldTypeFromMarker($marker, $arguments['mail']['form'])
				)
			);
			$i++;
		}

		$this->request->setArguments($newArguments);
		$this->request->setArgument('field', NULL);
	}

	/**
	 * Assigns all values, which should be available in all views
	 *
	 * @return void
	 */
	protected function assignForAll() {
		$this->view->assign('languageUid', ($GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] ? $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] : 0));
		$this->view->assign('Pid', $GLOBALS['TSFE']->id);
	}

	/**
	 * Deactivate errormessages in flashmessages
	 *
	 * @return bool
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}
}