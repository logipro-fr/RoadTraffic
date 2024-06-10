Feature: Retrieve real-time traffic information
  As a user of the traffic API
  I want to retrieve real-time traffic information
  In order to efficiently plan my travels

  Scenario Outline: Successful retrieval of traffic information for a specific location
    Given I select the traffic API provider <api> for traffic
    When I request to the traffic API with <coordinatesLat> and <coordinatesLong>
    Then I receive a successful response containing real-time traffic information
    And the response includes details such as traffic level, reported incidents, and estimated delays

      Examples:
          |     api       | coordinatesLat  | coordinatesLong  |
          |    "TomTom"   |     45.995      |       4.721      |


