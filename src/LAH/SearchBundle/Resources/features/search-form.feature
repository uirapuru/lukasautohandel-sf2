Feature: I can search cars using search form on frontpage and on catalog
  Background:
    Given I am on "/list"

    Scenario: I can set type, brand and model
    When I select "Opel" brand
    Then I dont see any models
    When I select "VolksWagen" brand
    Then I can see models "Golf, Passat, Colt"
    When I deselect brand
    Then I can see models "Golf, Passat, Colt, A3, A4, R6"
