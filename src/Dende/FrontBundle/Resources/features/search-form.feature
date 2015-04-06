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

#    Scenario: Resetted form gets all the cars
#    When I reset search form
#    And I submit search form
#    Then I can see 20 cars in results
#    And I can see 20 cars in results caption

#    Scenario Outline: I can filter out cars by type
#    When I select "<variant>" type
#    And I submit search form
#    Then I can see <count> cars in results
#    And I can see <count> cars in results caption
#
#    Examples:
#    | variant   | count |
#    | Sedan     | 11    |
#    | Coupe     | 5     |
#    | Hatchback | 0     |
#    | Combi     | 4     |

#    Scenario Outline: I can filter out cars by brand
#    When I reset search form
#    And I select "<brand>" brand
#    And I submit search form
#    Then I can see <count> cars in results
#    And I can see <count> cars in results caption
#
#    Examples:
#    | brand      | count |
#    | Opel       | 0     |
#    | VolksWagen | 12    |
#    | Nissan     | 0     |
#    | BMW        | 0     |
#    | Audi       | 8     |
#
#    Scenario Outline: I can filter out cars by model
#    When I reset search form
#    And I select "<model>" model
#    And I submit search form
#    Then I can see <count> cars in results
#    And I can see <count> cars in results caption
#
#  Examples:
#    | model      | count |
#    | Golf       | 3     |
#    | Passat     | 5     |
#    | Colt       | 4     |
#    | A3         | 0     |
#    | A4         | 4     |
#    | R6         | 4     |
#
#    Scenario Outline: I can filter out cars by mixed values
#    When I reset search form
#    And I select "<type>" type
#    And I select "<brand>" brand
#    And I select "<model>" model
#    And I submit search form
#    Then I can see <count> cars in results
#    And I can see <count> cars in results caption
#
#  Examples:
#    | type   | brand      | model      | count |
#    | Sedan  | VolksWagen | Golf       | 3     |
#    | Coupe  | VolksWagen | Golf       | 0     |
#    | Combi  | Audi       | A3         | 0     |
#    | Combi  | Audi       | A4         | 4     |
