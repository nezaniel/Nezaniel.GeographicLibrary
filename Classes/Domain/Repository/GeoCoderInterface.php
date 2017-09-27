<?php

namespace Nezaniel\GeographicLibrary\Domain\Repository;

/*                                                                               *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               */
use Neos\Flow\Annotations as Flow;
use Nezaniel\GeographicLibrary\Application;
use Nezaniel\GeographicLibrary\Domain;

/**
 * The geo coding interface to be implemented by adapters
 */
interface GeoCoderInterface
{
    /**
     * @param string $address
     * @return Application\Value\Coordinates
     * @throws Domain\Exception\NoSuchCoordinatesException If no coordinates could be found
     */
    public function fetchCoordinatesByAddress(string $address): Application\Value\Coordinates;

    /**
     * @param string $zip
     * @param string $countryCode The two character ISO 3166-1 country code
     * @return Application\Value\Coordinates
     * @throws Domain\Exception\NoSuchCoordinatesException If no coordinates could be found
     */
    public function fetchCoordinatesByPostalCode(string $zip, string $countryCode): Application\Value\Coordinates;
}
