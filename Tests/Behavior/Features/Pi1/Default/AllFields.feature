# Features/Pi1/Default/AllFields.feature
@Pi1DefaultAllFields
Feature: AllFields
  In order to see a word definition
  As a website user
  I need to be able to submit a form

  # L=0
  Scenario: Check if AllFields Form is rendered correctly
    Given I am on "/index.php?id=10"
    Then I should see "Input (E-Mail)"
    Then I should see "Textarea"
    Then I should see "Select Statisch"
    Then I should see "Select TypoScript"
    Then I should see "Select Multi"
    Then I should see "Check"
    Then I should see "Radio"
    Then I should see "country"
    Then I should see "Willkommen zum powermail Testparcour"
    Then I should see "Fett"
    Then I should see "Password"
    Then I should see "Bitte erneut eintragen"
    Then I should see "Upload"
    Then I should see "Date"
    Then I should see "Location"
    Then I should see "yellow[\n]green"
    Then I should see "Captcha"