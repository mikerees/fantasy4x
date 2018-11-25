Feature:
  In order to prove that Behat works as intended
  We want to test the home page for a phrase

  Scenario: Root Test
    Given I am on the homepage
    Then I should see "Laravel"

  Scenario: Dashboard is locked to guests
    When I go to "home"
    Then the url should match "home"
