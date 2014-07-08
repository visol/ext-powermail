# Features/Pi1/Default/TypoScriptPrefill.feature
Feature: TypoScriptPrefill
  In order to see a word definition
  As a website user
  I need to be able to submit a form

@typoscriptprefill
  Scenario: Check if Form can be prefilled with TypoScript Configuration
    Given I am on "/index.php?id=16"
    Then I should see "Input"
    Then I should see "Textarea"
    Then I should see "Select"
    Then I should see "Select Multi"
    Then I should see "Check"
    Then I should see "Radio"