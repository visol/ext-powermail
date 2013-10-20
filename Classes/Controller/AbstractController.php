<?php

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
class Tx_Powermail_Controller_AbstractController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * formRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_FormRepository
	 *
	 * @inject
	 */
	protected $formRepository;

	/**
	 * mailRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_MailRepository
	 *
	 * @inject
	 */
	protected $mailRepository;

	/**
	 * answerRepository
	 *
	 * @var Tx_Powermail_Domain_Repository_AnswerRepository
	 *
	 * @inject
	 */
	protected $answerRepository;

	/**
	 * @var Tx_Extbase_SignalSlot_Dispatcher
	 *
	 * @inject
	 */
	protected $signalSlotDispatcher;

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
	 * Instance for Misc Functions
	 *
	 * @var Tx_Powermail_Utility_Div
	 *
	 * @inject
	 */
	protected $div;

	/**
	 * message Class
	 *
	 * @var string
	 */
	protected $messageClass = 'error';

	/**
	 * Deactivate errormessages in flashmessages
	 *
	 * @return bool
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}
}