<?php

namespace Nezaniel\GeographicLibrary\Application\Eel\Helper;

/*                                                                              *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              */
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


    public function parse(float $latitude = 0.00, float $longitude = 0.00): Application\Value\Coordinates
    {
        return new Application\Value\Coordinates($latitude, $longitude);
    }

    public function parseString($serializedCoordinates): Application\Value\Coordinates
    {
        return Application\Value\Coordinates::fromString($serializedCoordinates);
    }

    /**
     * @param string $postalCode
     * @param string $countryCode
     * @return Application\Value\Coordinates|NULL
     */
    public function fromPostalCode($postalCode, $countryCode)
    {
        return $this->geoCodingService->fetchCoordinatesByPostalCode($postalCode, $countryCode);
    }

    /**
     * @param string $address
     * @return Application\Value\Coordinates|NULL
     */
    public function fromAddress($address)
    {
        return $this->geoCodingService->fetchCoordinatesByAddress($address);
    }

    public function distance(Application\Value\Coordinates $a, Application\Value\Coordinates $b): float
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
