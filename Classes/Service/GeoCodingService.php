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
use Nezaniel\GeographicLibrary\Service\Exception\NoSuchCoordinatesException;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cache\Frontend\VariableFrontend;

/**
 * @Flow\Scope("singleton")
 */
class GeoCodingService
{
    /**
     * @Flow\Inject
     * @var GeoCodingAdapterInterface
     */
    protected $geoCodingAdapter;

    /**
     * @var array
     */
    protected $postalCodeCoordinates = [];

    /**
     * @var array
     */
    protected $addressCoordinates = [];

    /**
     * @var VariableFrontend
     */
    protected $cache;


    /**
     * @param VariableFrontend $cache
     */
    public function setCache(VariableFrontend $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Initialize the object and load caches
     */
    public function initializeObject()
    {
        $this->postalCodeCoordinates = $this->cache->get('postalCodeCoordinates') ?: [];
        $this->addressCoordinates = $this->cache->get('addressCoordinates') ?: [];
    }


    /**
     * @param string $address The address string
     * @return array|NULL The coordinates or NULL if none could be fetched
     */
    public function fetchCoordinatesByAddress($address)
    {
        $addressHash = sha1(mb_strtolower($address));
        if (!isset($this->addressCoordinates[$addressHash])) {
            try {
                $this->addressCoordinates[$addressHash] = $this->geoCodingAdapter->fetchCoordinatesByAddress($address);
                $this->addressCoordinates[$addressHash]['address'] = $address;
                $this->cache->set('addressCoordinates', $this->addressCoordinates);
            } catch (NoSuchCoordinatesException $exception) {
                return null;
            }
        }
        return $this->addressCoordinates[$addressHash];
    }

    /**
     * @param string $postalCode The zip code
     * @param string $countryCode The two character ISO 3166-1 country code
     * @return array|NULL The coordinates or NULL if none could be fetched
     */
    public function fetchCoordinatesByPostalCode($postalCode, $countryCode)
    {
        $cacheIdentifier = $postalCode . '-' . $countryCode;
        if (!isset($this->postalCodeCoordinates[$cacheIdentifier])) {
            try {
                $this->postalCodeCoordinates[$cacheIdentifier] = $this->geoCodingAdapter->fetchCoordinatesByPostalCode($postalCode, $countryCode);
                $this->cache->set('postalCodeCoordinates', $this->postalCodeCoordinates);
            } catch (NoSuchCoordinatesException $exception) {
                return null;
            }
        }
        return $this->postalCodeCoordinates[$cacheIdentifier];
    }
}
