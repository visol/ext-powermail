<?php

use Behat\Behat\Context\ClosuredContextInterface,
	Behat\Behat\Context\TranslatedContextInterface,
	Behat\Behat\Context\BehatContext,
	Behat\Behat\Exception\PendingException,
	Behat\Behat\Context\Step\Given,
	Behat\Behat\Context\Step\Then,
	Behat\Behat\Context\Step\When,
	Behat\Gherkin\Node\PyStringNode,
	Behat\Gherkin\Node\TableNode;

/**
 * Class FeatureContext
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext {

	/**
	 * Wait for X seconds
	 *
	 * @Given /^I wait "([^"]*)" seconds$/
	 *
	 * @param string $seconds
	 * @return void
	 */
	public function iWaitSeconds($seconds) {
		sleep($seconds);
	}
}