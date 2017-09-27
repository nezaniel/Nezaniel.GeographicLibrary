<?php
namespace Nezaniel\GeographicLibrary\Application\Service;

/*                                                                               *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               */
use Nezaniel\GeographicLibrary\Application;
use Nezaniel\GeographicLibrary\Domain;
use Neos\Flow\Annotations as Flow;
use Neos\Cache\Frontend\VariableFrontend;

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
     * @var array|Application\Value\Coordinates[]
     */
    protected $postalCodeCoordinates = [];

    /**
     * @var array|Application\Value\Coordinates[]
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
     * @return Application\Value\Coordinates|NULL The coordinates or NULL if none could be fetched
     */
    public function fetchCoordinatesByAddress(string $address)
    {
        $addressHash = sha1(mb_strtolower($address));
        if (!isset($this->addressCoordinates[$addressHash])) {
            try {
                $this->addressCoordinates[$addressHash] = $this->geoCoder->fetchCoordinatesByAddress($address);
                $this->cache->set('addressCoordinates', $this->addressCoordinates);
            } catch (Domain\Exception\NoSuchCoordinatesException $exception) {
                return null;
            }
        }
        return $this->addressCoordinates[$addressHash];
    }

    /**
     * @param string $postalCode The zip code
     * @param string $countryCode The two character ISO 3166-1 country code
     * @return Application\Value\Coordinates|NULL The coordinates or NULL if none could be fetched
     */
    public function fetchCoordinatesByPostalCode(string $postalCode, string $countryCode)
    {
        $cacheIdentifier = $postalCode . '-' . $countryCode;
        if (!isset($this->postalCodeCoordinates[$cacheIdentifier])) {
            try {
                $this->postalCodeCoordinates[$cacheIdentifier] = $this->geoCoder->fetchCoordinatesByPostalCode($postalCode, $countryCode);
                $this->cache->set('postalCodeCoordinates', $this->postalCodeCoordinates);
            } catch (Domain\Exception\NoSuchCoordinatesException $exception) {
                return null;
            }
        }
        return $this->postalCodeCoordinates[$cacheIdentifier];
    }
}
