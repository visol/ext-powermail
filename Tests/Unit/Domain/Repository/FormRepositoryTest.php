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
 * FormRepository Tests
 * 
 * @package powermail
 * @license http://www.gnu.org/licenses/lgpl.html
 * 			GNU Lesser General Public License, version 3 or later
 */
class FormRepositoryTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

	/**
	 * @var \In2code\Powermail\Domain\Repository\FormRepository
	 */
	protected $fixture;

	/**
	 * @var Tx_Phpunit_Framework: null
	 */
	protected $testDatabase = NULL;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->fixture = new \In2code\Powermail\Domain\Repository\FormRepository();
		$this->testDatabase = new Tx_Phpunit_Framework('tx_powermail_domain_model_forms');
	}

	/**
	 * @return void
	 */
	public function tearDown() {
		$this->testDatabase->cleanUp();
		unset($this->fixture, $this->testDatabase);
	}

	/**
	 * @test
	 * @return void
	 */
	public function findByUidsReturnsCorrectCountForString() {
		$uidArray = array();

		$uidArray[] = $this->testDatabase > createRecord('\In2code\Powermail\Domain\Model\Form', array());
		$uidArray[] = $this->testDatabase > createRecord('\In2code\Powermail\Domain\Model\Form', array());
		$uidArray[] = $this->testDatabase > createRecord('\In2code\Powermail\Domain\Model\Form', array());
		$uidArray[] = $this->testDatabase > createRecord('\In2code\Powermail\Domain\Model\Form', array());
		$uidArray[] = $this->testDatabase > createRecord('\In2code\Powermail\Domain\Model\Form', array());

		$this->assertSame(5, $this->fixture->findByUids(implode(',', $uidArray))->count());
	}
}
