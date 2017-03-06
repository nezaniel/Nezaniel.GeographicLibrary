<?php
namespace Nezaniel\GeographicLibrary\Eel\Helper;

/*                                                                              *
 * This script belongs to the TYPO3 Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              *
 * It is free software; you can redistribute it and/or modify it under          *
 * the terms of the GNU Lesser General Public License, either version 3         *
 * of the License, or (at your option) any later version.                       *
 *                                                                              *
 * The TYPO3 project - inspiring people to share!                               *
 *                                                                              */
use Nezaniel\GeographicLibrary\Service\GeoCodingService;
use TYPO3\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Utility\Arrays;

/**
 * Date helpers for Eel contexts
 */
class LocationHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var GeoCodingService
     */
    protected $geoCodingService;


    /**
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function parse($latitude = 0.00, $longitude = 0.00)
    {
        return [
            'latitude' => (float)$latitude,
            'longitude' => (float)$longitude
        ];
    }

    /**
     * @param string $serializedLocation
     * @return array
     */
    public function parseString($serializedLocation)
    {
        $location = Arrays::trimExplode(',', $serializedLocation);
        return [
            'latitude' => (float)$location[0],
            'longitude' => (float)$location[1]
        ];
    }

    /**
     * @param string $postalCode
     * @param string $countryCode
     * @return array|NULL
     */
    public function fromPostalCode($postalCode, $countryCode)
    {
        return $this->geoCodingService->fetchCoordinatesByPostalCode($postalCode, $countryCode);
    }

    /**
     * @param string $address
     * @return array|NULL
     */
    public function fromAddress($address)
    {
        return $this->geoCodingService->fetchCoordinatesByAddress($address);
    }

    /**
     * @param array $location
     * @return float
     */
    public function latitude(array $location)
    {
        return (float)$location['latitude'];
    }

    /**
     * @param array $location
     * @return float
     */
    public function longitude(array $location)
    {
        return (float)$location['longitude'];
    }

    /**
     * Calculates the distance between two points on the surface of a sphere
     *
     * @param array $a The geographical coordinates of the first point
     * @param array $b The geographical coordinates of the second point
     * @return float The calculated distance in km
     * @link Wikipedia <https://en.wikipedia.org/wiki/Sphere>
     */
    public function distance(array $a, array $b)
    {
        return (6370
            * acos(
                sin(deg2rad((double)$a['latitude']))
                * sin(deg2rad((double)$b['latitude']))
                + cos(deg2rad((double)$a['latitude']))
                * cos(deg2rad((double)$b['latitude']))
                * cos(deg2rad((double)$b['longitude']) - deg2rad((double)$a['longitude']))
            )
        );
    }


    /**
     * All methods are considered safe
     *
     * @param string $methodName
     * @return boolean
     */
    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
