Feature: I can add cars using form
  Background:
    Given I am logged in as "admin" using "admin"
    And I am on "/admin/cars/add"

  Scenario: I can add a new type of a car
    When I click "a#add-new-car-type"
    Then I can't see "car.form.label.type" row
    And I can see "car.form.label.type" row
    And I fill in "dende_form_car_add_type_name" with "some test"

    When I click "a#add-new-car-type-close"
    Then I can't see "car.form.label.add_type.name" row
    And I can see "car.form.label.add_type" row

    When I click "a#add-new-car-type"
    Then I can't see "Typ" row
    And I can see "Nowy typ" row
    And the "input#dende_form_car_add_type_name" element should contain ""

  Scenario: I can add a new color of a car
    When I click "a#add-new-car-color"
    Then I can't see "Kolor" row
    And I can see "Nowy kolor" row
    And I fill in "dende_form_car_add_color_translations_pl_name" with "some color"

    When I click "a#add-new-car-color-close"
    Then I can't see "Nowy kolor" row
    And I can see "Kolor" row

    When I click "a#add-new-car-color"
    Then I can't see "Kolor" row
    And I can see "Nowy kolor" row
    And the "input#dende_form_car_add_color_translations_pl_name" element should contain ""

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