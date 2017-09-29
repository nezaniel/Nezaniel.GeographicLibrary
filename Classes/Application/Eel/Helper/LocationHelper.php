<?php

namespace Nezaniel\GeographicLibrary\Application\Eel\Helper;

/*                                                                              *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              */
use Neos\Utility\Arrays;
use Nezaniel\GeographicLibrary\Application;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;

/**
 * Location helpers for Eel contexts
 */
class LocationHelper implements ProtectedContextAwareInterface
{
    /**
     * @Flow\Inject
     * @var Application\Service\GeoCodingService
     */
    protected $geoCodingService;


    public function parse(float $latitude = 0.00, float $longitude = 0.00): Application\Value\GeoCoordinates
    {
        return new Application\Value\GeoCoordinates($latitude, $longitude);
    }

    public function parseString(string $serializedCoordinates): Application\Value\GeoCoordinates
    {
        list($latitude, $longitude) = Arrays::trimExplode(',', $serializedCoordinates);

        return new Application\Value\GeoCoordinates($latitude, $longitude);
    }

    /**
     * @param string $postalCode
     * @param string $countryCode
     * @return Application\Value\GeoCoordinates|NULL
     */
    public function fromPostalCode($postalCode, $countryCode)
    {
        return $this->geoCodingService->fetchCoordinatesByPostalCode($postalCode, $countryCode);
    }

    /**
     * @param string $address
     * @return Application\Value\GeoCoordinates|NULL
     */
    public function fromAddress($address)
    {
        return $this->geoCodingService->fetchCoordinatesByAddress($address);
    }

    public function distance(Application\Value\GeoCoordinates $a, Application\Value\GeoCoordinates $b): float
    {
        return $a->getDistance($b);
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
