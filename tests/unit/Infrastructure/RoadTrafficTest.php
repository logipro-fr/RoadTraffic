<?php

namespace RoadTraffic\Tests\Infrastructure;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Infrastructure\RoadTraffic;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtomIncident;

use function Safe\file_get_contents;

class RoadTrafficTest extends TestCase
{
    public function testUrlApplication(): void
    {
        $neighborsArray = [];

        $contentHotPoint = file_get_contents(getcwd() . "/tests/resources/hot_point_6_departments.json");

        $contentDecode = json_decode($contentHotPoint);
        $this->assertIsArray($contentDecode);
        $lat = $contentDecode[0]->hot_points[0]->lat;
        $lon = $contentDecode[0]->hot_points[0]->lon;

        foreach ($contentDecode[0]->hot_points[0]->neighbors as $neighbor) {
            $neighborsArray[] = array(
                'lat' => $neighbor->lat,
                'lon' => $neighbor->lon
            );
        }

        $request = new RequestTomtom(new GeographicCoordinates(new Latitude($lat), new Longitude($lon)));
        $requestIncident = new RequestTomtomIncident([4.7,45.982,4.742,46.008]);
        $trafficApi = new RoadTraffic();

        $responseFlowTraffic = $trafficApi->executeRequestFlowTrafficUrlApi($request);
        $responseIncidentTraffic = $trafficApi->executeRequestIncidentTrafficUrlApi($requestIncident);
        $this->assertEquals("https://api.tomtom.com/traffic/services/5/incidentDetails?key=FakeApiKeyTomtom&bbox=4.7,45.982,4.742,46.008&fields={incidents{type,geometry{type,coordinates},properties{id,iconCategory,magnitudeOfDelay,startTime,endTime,from,to,length,roadNumbers,probabilityOfOccurrence,timeValidity,delay,events{description,code}}}}", $responseIncidentTraffic->__toString());
        $this->assertEquals("https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=FakeApiKeyTomtom&point=45.995,4.721", $responseFlowTraffic->__toString());
    }
}
