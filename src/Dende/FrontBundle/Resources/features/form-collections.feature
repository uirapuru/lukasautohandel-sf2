Feature: I can add cars using form
  Background:
    Given I am logged in as "admin" using "admin"
    And I am on "/admin/cars/add"

  Scenario: Add 5 images and remove 3 of them
    Given images list is empty
    Then I add 5 images
    And I remove 3 images
    And I have 2 images

  Scenario: Add 5 prices and remove 3 of them
    Given prices list is empty
    Then I add 5 prices
    And I remove 3 prices
    And I have 2 prices