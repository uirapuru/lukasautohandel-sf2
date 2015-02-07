Feature: I can add cars using form
  Background:
    Given I am on "/cars/add"

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

  Scenario: I can add a new type of a car
    When I click "a#add-new-car-type"
    Then I can't see "Typ" row
    And I can see "Nowy typ" row
    And I fill in "dende_form_car_add_type_name" with "some test"

    When I click "a#add-new-car-type-close"
    Then I can't see "Nowy typ" row
    And I can see "Typ" row

    When I click "a#add-new-car-type"
    Then I can't see "Typ" row
    And I can see "Nowy typ" row
    And the "input#dende_form_car_add_type_name" element should contain ""

  Scenario: I can add a new color of a car
    When I click "a#add-new-car-color"
    Then I can't see "Kolor" row
    And I can see "Nowy kolor" row
    And I fill in "dende_form_car_add_color_name" with "some color"
    And I fill in "dende_form_car_add_color_hex" with "#ff0000"

    When I click "a#add-new-car-color-close"
    Then I can't see "Nowy kolor" row
    And I can see "Kolor" row

    When I click "a#add-new-car-color"
    Then I can't see "Kolor" row
    And I can see "Nowy kolor" row
    And the "input#dende_form_car_add_color_name" element should contain ""
    And the "input#dende_form_car_add_color_hex" element should contain ""

  Scenario: I can add a new model and a brand of a car
    When I click "a#add-new-car-model"
    Then I can't see "Model" row
    And I can see "Nowy model" row
    And I fill in "dende_form_car_add_model_name" with "some model"
    And I fill in "dende_form_car_add_model_brand" with "some brand"

    When I click "a#add-new-car-model-close"
    Then I can't see "Nowy model" row
    And I can see "Model" row

    When I click "a#add-new-car-model"
    Then I can't see "Model" row
    And I can see "Nowy model" row
    And the "input#dende_form_car_add_model_name" element should contain ""
    And the "input#dende_form_car_add_model_brand" element should contain ""