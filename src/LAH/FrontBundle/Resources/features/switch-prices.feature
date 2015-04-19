Feature: I can add cars using form
  Background:
    Given I am logged in as "admin" using "admin"
    And I am on "/admin/cars/add"

  Scenario: Add price and change it's currency
    Given prices list is empty
    And I add 3 prices
    And I set 1 price currency to "EUR"
    And I set 2 price currency to "USD"
    And I set 3 price currency to "PLN"
    Then I can see 1 price currency set to "€"
    And I can see 2 price currency set to "$"
    And I can see 3 price currency set to "PLN"

    When I fill in "lah_form_car_prices_0_amount" with "100"
    And I fill in "lah_form_car_prices_1_amount" with "10000"
    And I fill in "lah_form_car_prices_2_amount" with "-15"
    And I submit form "addCar"
    Then I should see text matching "flash.car_edit.errors"
    Then I should see text matching "validator.minimal_price_must_be_over_0"

    Then I can see 1 price currency set to "€"
    And I can see 2 price currency set to "$"
    And I can see 3 price currency set to "PLN"

    When I fill in "lah_form_car_prices_2_amount" with "100"

    And I submit form "addCar"
    Then I should see text matching "flash.car_edit.errors"
    Then I should not see text matching "validator.minimal_price_must_be_over_0"
