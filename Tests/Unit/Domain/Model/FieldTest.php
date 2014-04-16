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
 * Test case for class \In2code\Powermail\Domain\Model\Field
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
class FieldTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \In2code\Powermail\Domain\Model\Field
	 */
	protected $generalValidatorMock;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->generalValidatorMock = $this->getAccessibleMock('\In2code\Powermail\Domain\Model\Field', array('dummy'));
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		unset($this->generalValidatorMock);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTitleReturnsInitialValueForString() {

	}

	/**
	 * @test
	 * @return void
	 */
	public function setTitleForStringSetsTitle() {
		$this->generalValidatorMock->setTitle('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getTitle()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getTypeReturnsInitialValueForSting() {
		$this->generalValidatorMock->setType('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getType()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setTypeForIntegerSetsType() {
		$this->generalValidatorMock->setType(12);

		$this->assertSame(
			12,
			$this->generalValidatorMock->getType()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getPrefillValueReturnsInitialValueForString() {

	}

	/**
	 * @test
	 * @return void
	 */
	public function setPrefillValueForStringSetsPrefillValue() {
		$this->generalValidatorMock->setPrefillValue('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getPrefillValue()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getValidationReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->generalValidatorMock->getValidation()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setValidationForIntegerSetsValidation() {
		$this->generalValidatorMock->setValidation(12);

		$this->assertSame(
			12,
			$this->generalValidatorMock->getValidation()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getCssReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->generalValidatorMock->getCss()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setCssForStringSetsCss() {
		$this->generalValidatorMock->setCss('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->generalValidatorMock->getCss()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function getFeuserValueReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->generalValidatorMock->getFeuserValue()
		);
	}

	/**
	 * @test
	 * @return void
	 */
	public function setFeuserValueForStringSetsFeuserValue() {
		$this->generalValidatorMock->setFeuserValue('my FrontendUser');

		$this->assertSame(
			'my FrontendUser',
			$this->generalValidatorMock->getFeuserValue()
		);
	}

}