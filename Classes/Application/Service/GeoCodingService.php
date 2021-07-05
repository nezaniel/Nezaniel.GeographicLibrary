<?php

namespace Nezaniel\GeographicLibrary\Application\Service;

/*                                                                               *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               */

use Psr\Log\LoggerInterface;
use Nezaniel\GeographicLibrary\Application;
use Nezaniel\GeographicLibrary\Domain;
use Neos\Flow\Annotations as Flow;
use Neos\Cache\Frontend\VariableFrontend;
use Psr\Log\LogLevel;

/**
 * @Flow\Scope("singleton")
 */
class GeoCodingService
{
    /**
     * @Flow\Inject
     * @var Domain\Repository\GeoCoderInterface
     */
    protected $geoCoder;

    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $systemLogger;

    /**
     * @var array|Application\Value\GeoCoordinates[]
     */
    protected $postalCodeCoordinates = [];

    /**
     * @var array|Application\Value\GeoCoordinates[]
     */
    protected $addressCoordinates = [];

    /**
     * @var array|Application\Value\GeoCoordinates[]
     */
    protected $enrichedCoordinates = [];

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
        $this->enrichedCoordinates = $this->cache->get('enrichedCoordinates') ?: [];
    }


    /**
     * @param string $address The address string
     * @return Application\Value\GeoCoordinates|null The coordinates or null if none could be fetched
     */
    public function fetchCoordinatesByAddress(string $address)
    {
        $addressHash = sha1(mb_strtolower($address));
        if (!isset($this->addressCoordinates[$addressHash])) {
            try {
                $this->addressCoordinates[$addressHash] = $this->geoCoder->fetchCoordinatesByAddress($address);
                $this->cache->set('addressCoordinates', $this->addressCoordinates);
            } catch (Domain\Exception\NoSuchCoordinatesException $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->addressCoordinates[$addressHash];
    }

    /**
     * @param string $postalCode The zip code
     * @param string $countryCode The two character ISO 3166-1 country code
     * @return Application\Value\GeoCoordinates|null The coordinates or null if none could be fetched
     */
    public function fetchCoordinatesByPostalCode(string $postalCode, string $countryCode)
    {
        $cacheIdentifier = $postalCode . '-' . $countryCode;
        if (!isset($this->postalCodeCoordinates[$cacheIdentifier])) {
            try {
                $this->postalCodeCoordinates[$cacheIdentifier] = $this->geoCoder->fetchCoordinatesByPostalCode($postalCode, $countryCode);
                $this->cache->set('postalCodeCoordinates', $this->postalCodeCoordinates);
            } catch (Domain\Exception\NoSuchCoordinatesException $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->postalCodeCoordinates[$cacheIdentifier];
    }

    /**
     * @param Application\Value\GeoCoordinates $coordinates
     * @return Application\Value\GeoCoordinates|null
     */
    public function enrichGeoCoordinates(Application\Value\GeoCoordinates $coordinates)
    {
        $cacheIdentifier = $coordinates->getLatitude() . '-' . $coordinates->getLongitude();
        if (!isset($this->enrichedCoordinates[$cacheIdentifier])) {
            try {
                $this->enrichedCoordinates[$cacheIdentifier] = $this->geoCoder->enrichGeoCoordinates($coordinates);
                $this->cache->set('enrichedCoordinates', $this->enrichedCoordinates);
            } catch (Domain\Exception\NoSuchCoordinatesException $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->enrichedCoordinates[$cacheIdentifier];
    }
}
