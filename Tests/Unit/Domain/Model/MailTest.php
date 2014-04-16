<?php
namespace In2code\Powermail\Tests;

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
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \In2code\Powermail\Domain\Model\Mail
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html 
 * 			GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage powermail
 *
 * @author Alex Kellner <alexander.kellner@in2code.de>
 */
class MailTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \In2code\Powermail\Domain\Model\Mail
	 */
	protected $generalValidatorMock;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->generalValidatorMock = $this->getAccessibleMock('\In2code\Powermail\Domain\Model\Mail', array('dummy'));
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->generalValidatorMock);
	}

	/**
	 * @test
	 */
	public function getSenderNameReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSenderName()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsSenderName() {
		$this->generalValidatorMock->setSenderName('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getSenderName()
		);
	}

	/**
	 * @test
	 */
	public function getSenderMailReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSenderMail()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsSenderMail() {
		$this->generalValidatorMock->setSenderMail('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getSenderMail()
		);
	}


	/**
	 * @test
	 */
	public function getSubjectReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSubject()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsSubject() {
		$this->generalValidatorMock->setSubject('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getSubject()
		);
	}

	/**
	 * @test
	 */
	public function getReceiverMailReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getReceiverMail()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringReceiverMail() {
		$this->generalValidatorMock->setReceiverMail('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getReceiverMail()
		);
	}

	/**
	 * @test
	 */
	public function getBodyReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getBody()
		);
	}

	/**
	 * @test
	 */
	public function setBodyForStringSetsBody() {
		$this->generalValidatorMock->setBody('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getBody()
		);
	}

	/**
	 * @test
	 */
	public function getFeuserReturnsInitialValueForString(){
		$this->assertSame(
			NULL,
			$this->generalValidatorMock->getFeuser()
		);
	}

	/**
	 * @test
	 */
	public function getSenderipReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSenderIp()
		);
	}

	/**
	 * @test
	 */
	public function setsenderIpForStringSetssenderIp() {
		$this->generalValidatorMock->setSenderIp('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getSenderIp()
		);
	}

	/**
	 * @test
	 */
	public function getuserAgentReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSenderIp()
		);
	}

	/**
	 * @test
	 */
	public function setuserAgentForStringSetsUseragent() {
		$this->generalValidatorMock->setUserAgent('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getUserAgent()
		);
	}

	/**
	 * @test
	 */
	public function getSpamfactorReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getSpamFactor()
		);
	}

	/**
	 * @test
	 */
	public function setSpamfactorForStringSetsSpamfactor() {
		$this->generalValidatorMock->setSpamFactor('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getSpamFactor()
		);
	}


	/**
	 * @test
	 */
	public function getTimeReturnsInitialValueForNull(){
		$this->assertSame(
			NULL,
			$this->generalValidatorMock->getTime()
		);
	}

	/**
	 * @test
	 */
	public function setTimeForDatetimeSetsTime() {
		$now = mktime();
		$this->generalValidatorMock->setTime($now);
		$this->assertSame(
			$now,
			$this->generalValidatorMock->getTime()
		);
	}

	/**
	 * @test
	 */
	public function getFormReturnsInitialValueForNull(){
		$this->assertSame(
			NULL,
			$this->generalValidatorMock->getForm()
		);
	}

	/**
	 * @test
	 */
	public function setFormForTxPowermailDomainModelFormSetsForm() {
		$form = new \In2code\Powermail\Domain\Model\Form;
		$this->generalValidatorMock->setForm($form);
		$this->assertSame(
			$form,
			$this->generalValidatorMock->getForm()
		);
	}


	/**
	 * @test
	 */
	public function getAnswersReturnsInitialValueForNull(){
		$this->assertSame(
			NULL,
			$this->generalValidatorMock->getAnswers()
		);
	}

	/**
	 * @test
	 */
	public function setAnswersForTxPowermailDomainModelAnswerSetsAnswers() {
		$dummy = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
		$this->generalValidatorMock->setAnswers($dummy);
		$this->assertSame(
			$dummy,
			$this->generalValidatorMock->getAnswers()
		);
	}


	/**
	 * @test
	 */
	public function getCrdateReturnsInitialValueForNull(){
		$this->assertSame(
			NULL,
			$this->generalValidatorMock->getCrdate()
		);
	}

	/**
	 * @test
	 */
	public function setCrdateForDatetimeSetsCrdate() {
		$now = mktime();
		$this->generalValidatorMock->setCrdate($now);
		$this->assertSame(
			$now,
			$this->generalValidatorMock->getCrdate()
		);
	}

	/**
	 * @test
	 */
	public function getHiddenReturnsInitialValueForFalse(){
		$this->assertSame(
			FALSE,
			$this->generalValidatorMock->getHidden()
		);
	}

	/**
	 * @test
	 */
	public function setHiddenToTrue() {

		$this->generalValidatorMock->setHidden(TRUE);
		$this->assertSame(
			TRUE,
			$this->generalValidatorMock->getHidden()
		);
	}

	/**
	 * @test
	 */
	public function setmarketingRefererForStringSetsmarketingReferer() {
		$this->generalValidatorMock->setMarketingReferer('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getMarketingReferer()
		);
	}

	/**
	 * @test
	 */
	public function getmarketingBrowserLanguageReturnsInitialValueForString(){
		$this->assertSame(
			'',
			$this->generalValidatorMock->getMarketingBrowserLanguage()
		);
	}

	/**
	 * @test
	 */
	public function setmarketingBrowserLanguageForStringSetsmarketingBrowserLanguage() {
		$this->generalValidatorMock->setMarketingBrowserLanguage('Conceived at T3CON10');
		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getMarketingBrowserLanguage()
		);
	}
}