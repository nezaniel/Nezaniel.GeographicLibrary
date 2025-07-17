<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

/*                                                                               *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               */
use Neos\Flow\Annotations as Flow;

/**
 * The geocoding interface to be implemented by adapters
 */
interface GeoCoderInterface
{
    /**
     * @throws \Nezaniel\GeographicLibrary\CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function fetchCoordinatesByAddress(string $address): GeoCoordinates;

    /**
     * @throws \Nezaniel\GeographicLibrary\CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function fetchCoordinatesByPostalCode(string $postalCode, CountryCode $countryCode): GeoCoordinates;

    /**
     * @throws \Nezaniel\GeographicLibrary\CoordinatesCouldNotBeResolved If no coordinates could be found
     */
    public function enrichGeoCoordinates(GeoCoordinates $coordinates): GeoCoordinates;
}
