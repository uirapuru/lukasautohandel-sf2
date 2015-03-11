Feature: I can search cars using search form on frontpage and on catalog
  Background:
    Given I am on "/list"

  Scenario: I can set type, brand and model
    When I select "Opel" from "car_filters_brand"
    Then I can see only "Opel" cars models