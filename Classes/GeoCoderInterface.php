<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

/**
 * The geocoding interface to be implemented by adapters
 */
interface GeoCoderInterface
{
    /**
     * @throws CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function fetchCoordinatesByAddress(string $address): GeoCoordinates;

    /**
     * @throws CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function fetchCoordinatesByPostalCode(string $postalCode, CountryCode $countryCode): GeoCoordinates;

    /**
     * @throws CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function enrichGeoCoordinates(GeoCoordinates $coordinates): GeoCoordinates;
}
