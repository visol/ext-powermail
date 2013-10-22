<?php
namespace In2code\Powermail\Domain\Model;

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
 * Mail Model
 *
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 */
class Mail extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * senderName
	 *
	 * @var string
	 */
	protected $senderName = '';

	/**
	 * senderMail
	 *
	 * @var string
	 */
	protected $senderMail = '';

	/**
	 * subject
	 *
	 * @var string
	 */
	protected $subject = '';

	/**
	 * receiverMail
	 *
	 * @var string
	 */
	protected $receiverMail = '';

	/**
	 * body
	 *
	 * @var string
	 */
	protected $body = '';

	/**
	 * feuser
	 *
	 * @var \In2code\Powermail\Domain\Model\User
	 */
	protected $feuser = NULL;

	/**
	 * senderIp
	 *
	 * @var string
	 */
	protected $senderIp = '';

	/**
	 * userAgent
	 *
	 * @var string
	 */
	protected $userAgent = '';

	/**
	 * spamFactor
	 *
	 * @var string
	 */
	protected $spamFactor = '';

	/**
	 * time
	 *
	 * @var DateTime
	 */
	protected $time = NULL;

	/**
	 * form
	 *
	 * @var \In2code\Powermail\Domain\Model\Form
	 * @lazy
	 */
	protected $form = NULL;

	/**
	 * Powermail Answers
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\Powermail\Domain\Model\Answer>
	 * @lazy
	 */
	protected $answers = NULL;

	/**
	 * crdate
	 *
	 * @var DateTime
	 */
	protected $crdate = NULL;

	/**
	 * hidden
	 *
	 * @var bool
	 */
	protected $hidden = FALSE;

	/**
	 * marketingSearchterm
	 *
	 * @var string
	 */
	protected $marketingSearchterm = '';

	/**
	 * marketingReferer
	 *
	 * @var string
	 */
	protected $marketingReferer = '';

	/**
	 * marketingPayedSearchResult
	 *
	 * @var string
	 */
	protected $marketingPayedSearchResult = '';

	/**
	 * marketingLanguage
	 *
	 * @var string
	 */
	protected $marketingLanguage = '';

	/**
	 * marketingBrowserLanguage
	 *
	 * @var string
	 */
	protected $marketingBrowserLanguage = '';

	/**
	 * marketingFunnel
	 *
	 * @var string
	 */
	protected $marketingFunnel = '';

	/**
	 * __construct
	 */
	public function __construct() {
		$this->initStorageObjects();
	}

	/**
	 * Initializes all \TYPO3\CMS\Extbase\Persistence\ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		$this->answer = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the senderName
	 *
	 * @return string $senderName
	 */
	public function getSenderName() {
		return $this->senderName;
	}

	/**
	 * Sets the senderName
	 *
	 * @param string $senderName
	 * @return void
	 */
	public function setSenderName($senderName) {
		$this->senderName = $senderName;
	}

	/**
	 * Returns the senderMail
	 *
	 * @return string $senderMail
	 */
	public function getSenderMail() {
		return $this->senderMail;
	}

	/**
	 * Sets the senderMail
	 *
	 * @param string $senderMail
	 * @return void
	 */
	public function setSenderMail($senderMail) {
		$this->senderMail = $senderMail;
	}

	/**
	 * Returns the subject
	 *
	 * @return string $subject
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Sets the subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Returns the receiverMail
	 *
	 * @return string $receiverMail
	 */
	public function getReceiverMail() {
		return $this->receiverMail;
	}

	/**
	 * Sets the receiverMail
	 *
	 * @param string $receiverMail
	 * @return void
	 */
	public function setReceiverMail($receiverMail) {
		$this->receiverMail = $receiverMail;
	}

	/**
	 * Returns the body
	 *
	 * @return string $body
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Sets the body
	 *
	 * @param string $body
	 * @return void
	 */
	public function setBody($body) {
		$this->body = $body;
	}

	/**
	 * Returns the feuser
	 *
	 * @return \In2code\Powermail\Domain\Model\User $feuser
	 */
	public function getFeuser() {
		return $this->feuser;
	}

	/**
	 * Sets the feuser
	 *
	 * @param \In2code\Powermail\Domain\Model\User $feuser
	 * @return void
	 */
	public function setFeuser(\In2code\Powermail\Domain\Model\User $feuser) {
		$this->feuser = $feuser;
	}

	/**
	 * Returns the spamFactor
	 *
	 * @return string $spamFactor
	 */
	public function getSpamFactor() {
		return $this->spamFactor;
	}

	/**
	 * Sets the spamFactor
	 *
	 * @param string $spamFactor
	 * @return void
	 */
	public function setSpamFactor($spamFactor) {
		$this->spamFactor = $spamFactor;
	}

	/**
	 * Returns the time
	 *
	 * @return datetime $time
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * Sets the time
	 *
	 * @param datetime $time
	 * @return void
	 */
	public function setTime($time) {
		$this->time = $time;
	}

	/**
	 * Returns the senderIp
	 *
	 * @return string $senderIp
	 */
	public function getSenderIp() {
		return $this->senderIp;
	}

	/**
	 * Sets the senderIp
	 *
	 * @param string $senderIp
	 * @return void
	 */
	public function setSenderIp($senderIp) {
		$this->senderIp = $senderIp;
	}

	/**
	 * Returns the userAgent
	 *
	 * @return string $userAgent
	 */
	public function getUserAgent() {
		return $this->userAgent;
	}

	/**
	 * Sets the userAgent
	 *
	 * @param string $userAgent
	 * @return void
	 */
	public function setUserAgent($userAgent) {
		$this->userAgent = $userAgent;
	}

	/**
	 * Returns the form
	 *
	 * @return \In2code\Powermail\Domain\Model\Form $form
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * Sets the form
	 *
	 * @param \In2code\Powermail\Domain\Model\Form $form
	 * @return void
	 */
	public function setForm(\In2code\Powermail\Domain\Model\Form $form) {
		$this->form = $form;
	}

	/**
	 * Returns the answers
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\Powermail\Domain\Model\Answer> $answers
	 */
	public function getAnswers() {
		return $this->answers;
	}

	/**
	 * Sets the answers
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\In2code\Powermail\Domain\Model\Answer> $answers
	 * @return void
	 */
	public function setAnswers(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $answers) {
		$this->answers = $answers;
	}

	/**
	 * Adds an answer
	 *
	 * @param \In2code\Powermail\Domain\Model\Answer $answer
	 * @return void
	 */
	public function addAnswer(\In2code\Powermail\Domain\Model\Answer $answer) {
		$this->answers->attach($answer);
	}

	/**
	 * Removes an answer
	 *
	 * @param \In2code\Powermail\Domain\Model\Answer $answerToRemove The Fields to be removed
	 * @return void
	 */
	public function removeAnswer(\In2code\Powermail\Domain\Model\Answer $answerToRemove) {
		$this->answers->detach($answerToRemove);
	}

	/**
	 * Returns the crdate
	 *
	 * @return DateTime $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the crdate
	 *
	 * @param DateTime $crdate
	 * @return void
	 */
	public function setCrdate($crdate) {
		$this->crdate = $crdate;
	}

	/**
	 * Returns the hidden
	 *
	 * @return bool $hidden
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * Sets the hidden
	 *
	 * @param bool $hidden
	 * @return void
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * Returns the marketingSearchterm
	 *
	 * @return string $marketingSearchterm
	 */
	public function getMarketingSearchterm() {
		return $this->marketingSearchterm;
	}

	/**
	 * Sets the marketingSearchterm
	 *
	 * @param string $marketingSearchterm
	 * @return void
	 */
	public function setMarketingSearchterm($marketingSearchterm) {
		$this->marketingSearchterm = $marketingSearchterm;
	}

	/**
	 * Returns the marketingReferer
	 *
	 * @return string $marketingReferer
	 */
	public function getMarketingReferer() {
		return $this->marketingReferer;
	}

	/**
	 * Sets the marketingReferer
	 *
	 * @param string $marketingReferer
	 * @return void
	 */
	public function setMarketingReferer($marketingReferer) {
		$this->marketingReferer = $marketingReferer;
	}

	/**
	 * Returns the marketingPayedSearchResult
	 *
	 * @return string $marketingPayedSearchResult
	 */
	public function getMarketingPayedSearchResult() {
		return $this->marketingPayedSearchResult;
	}

	/**
	 * Sets the marketingPayedSearchResult
	 *
	 * @param string $marketingPayedSearchResult
	 * @return void
	 */
	public function setMarketingPayedSearchResult($marketingPayedSearchResult) {
		$this->marketingPayedSearchResult = $marketingPayedSearchResult;
	}

	/**
	 * Returns the marketingLanguage
	 *
	 * @return string $marketingLanguage
	 */
	public function getMarketingLanguage() {
		return $this->marketingLanguage;
	}

	/**
	 * Sets the marketingLanguage
	 *
	 * @param string $marketingLanguage
	 * @return void
	 */
	public function setMarketingLanguage($marketingLanguage) {
		$this->marketingLanguage = $marketingLanguage;
	}

	/**
	 * Returns the marketingBrowserLanguage
	 *
	 * @return string $marketingBrowserLanguage
	 */
	public function getMarketingBrowserLanguage() {
		return $this->marketingBrowserLanguage;
	}

	/**
	 * Sets the marketingBrowserLanguage
	 *
	 * @param string $marketingBrowserLanguage
	 * @return void
	 */
	public function setMarketingBrowserLanguage($marketingBrowserLanguage) {
		$this->marketingBrowserLanguage = $marketingBrowserLanguage;
	}

	/**
	 * Returns the marketingFunnel
	 *
	 * @return string $marketingFunnel
	 */
	public function getMarketingFunnel() {
		return unserialize($this->marketingFunnel);
	}

	/**
	 * Sets the marketingFunnel
	 *
	 * @param string $marketingFunnel
	 * @return void
	 */
	public function setMarketingFunnel($marketingFunnel) {
		$this->marketingFunnel = serialize($marketingFunnel);
	}
}