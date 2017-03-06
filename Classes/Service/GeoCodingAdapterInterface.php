<?php
namespace Nezaniel\GeographicLibrary\Service;

/*                                                                               *
 * This script belongs to the TYPO3 Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               *
 * It is free software; you can redistribute it and/or modify it under           *
 * the terms of the GNU General Public License, either version 3 of the          *
 * License, or (at your option) any later version.                               *
 *                                                                               *
 * The TYPO3 project - inspiring people to share!                                *
 *                                                                               */
use TYPO3\Flow\Annotations as Flow;

/**
 * The geo coding interface to be implemented by adapters
 */
interface GeoCodingAdapterInterface
{
    /**
     * Returns coordinates by address string
     * Return format:
     * [
     *     'latitude' => 47.11
     *     'longitude => 84.72
     * ]
     * @param string $address
     * @return array The coordinates
     * @throws Exception\NoSuchCoordinatesException If no coordinates could be found
     */
    public function fetchCoordinatesByAddress($address);

    /**
     * Returns coordinates by postal code
     * Return format:
     * [
     *     'latitude' => 47.11
     *     'longitude => 84.72
     * ]
     * @param string $zip
     * @param string $countryCode The two character ISO 3166-1 country code
     * @return array The coordinates
     * @throws Exception\NoSuchCoordinatesException If no coordinates could be found
     */
    public function fetchCoordinatesByPostalCode($zip, $countryCode);
}
