# Features/Pi1/Default/ShortForm.feature
  # TODO JavaScript for AJAX submit
Feature: ShortForm
  In order to see a word definition
  As a website user
  I need to be able to submit a form

  # L=0
  Scenario: Searching for a DefaultForm that does exist in german
    Given I am on "/index.php?id=9"
    Then I should see "ShortForm (AJAX)"
    Then I should see "Vorname"
    Then I should see "Nachname"
    Then I should see "E-Mail"
  # L=1
  Scenario: Searching for a DefaultForm that does exist in english
    Given I am on "/index.php?id=9&L=1"
    Then I should see "ShortForm (AJAX) EN"
    Then I should see "Firstname"
    Then I should see "Lastname"
    Then I should see "Email"