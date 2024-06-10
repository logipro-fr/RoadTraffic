<?php

namespace RoadTraffic\Application\Service\RoadTraffic;

use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;

class Calculate
{
    /** @return array{float} */
    public function calculateArea(
        Latitude $latitude,
        Longitude $longitude,
        float $distanceLat,
        float $distanceLong
    ): array {
        $minLon = $longitude->getLongitude() - $distanceLong;
        $minLat = $latitude->getLatitude() - $distanceLat;
        $maxLon = $longitude->getLongitude() + $distanceLong;
        $maxLat =  $latitude->getLatitude() + $distanceLat;

        return [$minLon,$minLat,$maxLon,$maxLat];
    }

    /**
    * @param array<int, array<string, mixed>> $neighbors
    * @return array<float>
    */
    public function calculateIncidentArea(Latitude $latitude, Longitude $longitude, array $neighbors): array
    {

        $maxLenghLat = null;
        $maxLenghLong = null;

        foreach ($neighbors as $coordinates) {
            $currentLat = abs($latitude->getLatitude() - $coordinates['lat']);
            $currentLong = abs($longitude->getLongitude() - $coordinates['lon']);

            $maxLenghLat = $this->maximalVar($currentLat, $maxLenghLat);
            $maxLenghLong = $this->maximalVar($currentLong, $maxLenghLong);
        }

        $minLon = $longitude->getLongitude() - $maxLenghLong;
        $minLat = $latitude->getLatitude() - $maxLenghLat;
        $maxLon = $longitude->getLongitude() + $maxLenghLong;
        $maxLat = $latitude->getLatitude() + $maxLenghLat;

        return [$minLon,$minLat,$maxLon,$maxLat];
    }

    public function maximalVar(float $current, ?float $max): float
    {
        return floatval(max($current, $max));
    }

    /** @param array<float> $resultCoordinates */
    public function calculateStringPoint(array $resultCoordinates): string
    {
        return implode(",", $resultCoordinates);
    }
}
