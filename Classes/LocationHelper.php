<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

use Neos\Utility\Arrays;
use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;

/**
 * Location helpers for Eel contexts
 */
class LocationHelper implements ProtectedContextAwareInterface
{
    public function __construct(
        private readonly GeoCodingService $geoCodingService,
    ) {
    }

    public function parse(float $latitude = 0.00, float $longitude = 0.00): GeoCoordinates
    {
        return new GeoCoordinates($latitude, $longitude);
    }

    public function parseString(string $serializedCoordinates): GeoCoordinates
    {
        list($latitude, $longitude) = Arrays::trimExplode(',', $serializedCoordinates);

        return new GeoCoordinates($latitude, $longitude);
    }

    public function fromPostalCode(string $postalCode, string $countryCode): ?GeoCoordinates
    {
        return $this->geoCodingService->fetchCoordinatesByPostalCode($postalCode, CountryCode::fromString($countryCode));
    }

    public function fromAddress(string $address): ?GeoCoordinates
    {
        return $this->geoCodingService->fetchCoordinatesByAddress($address);
    }

    public function distance(GeoCoordinates $a, GeoCoordinates $b): float
    {
        return $a->calculateSphereDistance($b);
    }

    /**
     * All methods are considered safe
     *
     * @param string $methodName
     */
    public function allowsCallOfMethod($methodName): true
    {
        return true;
    }
}
