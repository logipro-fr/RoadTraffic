Feature: Retrieve real-time incident information
  As a user of the traffic API
  I want to retrieve real-time incident information
  In order to efficiently plan my travels

  Scenario Outline: Successful retrieval of traffic information using hot point file
    Given I select the traffic API provider <api> for incident
    And Valid bbox point <bbox> 
    When I make a request to the traffic API for incidents
    Then I receive a successful response containing real-time incident information
    And the response includes details such as probability of occurrence, reported incidents, and estimated delays

      Examples:
            |      api      |             bbox              |
            |    "TomTom"   | "4.721,45.995,4.721,45.995" |



