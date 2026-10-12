<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

use Neos\Cache\Frontend\FrontendInterface;
use Neos\Flow\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Neos\Flow\Annotations as Flow;
use Psr\Log\LogLevel;

#[Flow\Scope('singleton')]
class GeoCodingService
{
    /**
     * @var array|GeoCoordinates[]
     */
    private array $postalCodeCoordinates = [];

    /**
     * @var array|GeoCoordinates[]
     */
    private array $addressCoordinates = [];

    /**
     * @var array|GeoCoordinates[]
     */
    private array $enrichedCoordinates = [];

    private FrontendInterface $cache;

    public function __construct(
        private readonly GeoCoderInterface $geoCoder,
        private readonly LoggerInterface $systemLogger,
        CacheManager $cacheManager,
    ) {
        $this->cache = $cacheManager->getCache('Nezaniel_GeographicLibrary_GeoCodingService_Cache');
    }

    /**
     * Initialize the object and load caches
     */
    public function initializeObject(): void
    {
        $this->postalCodeCoordinates = $this->cache?->get('postalCodeCoordinates') ?: [];
        $this->addressCoordinates = $this->cache?->get('addressCoordinates') ?: [];
        $this->enrichedCoordinates = $this->cache?->get('enrichedCoordinates') ?: [];
    }


    /**
     * @param string $address The address string
     * @return GeoCoordinates|null The coordinates or null if none could be fetched
     */
    public function fetchCoordinatesByAddress(string $address): ?GeoCoordinates
    {
        $addressHash = sha1(\mb_strtolower($address));
        if (!isset($this->addressCoordinates[$addressHash])) {
            try {
                $this->addressCoordinates[$addressHash] = $this->geoCoder->fetchCoordinatesByAddress($address);
                $this->cache->set('addressCoordinates', $this->addressCoordinates);
            } catch (CoordinatesCouldNotBeResolved $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->addressCoordinates[$addressHash];
    }

    /**
     * @param string $postalCode The postal code
     * @param CountryCode $countryCode The two character ISO 3166-1 country code
     * @return GeoCoordinates|null The coordinates or null if none could be fetched
     */
    public function fetchCoordinatesByPostalCode(string $postalCode, CountryCode $countryCode): ?GeoCoordinates
    {
        $cacheIdentifier = $postalCode . '-' . $countryCode->value;
        if (!isset($this->postalCodeCoordinates[$cacheIdentifier])) {
            try {
                $this->postalCodeCoordinates[$cacheIdentifier] = $this->geoCoder->fetchCoordinatesByPostalCode($postalCode, $countryCode);
                $this->cache->set('postalCodeCoordinates', $this->postalCodeCoordinates);
            } catch (CoordinatesCouldNotBeResolved $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->postalCodeCoordinates[$cacheIdentifier];
    }

    public function enrichGeoCoordinates(GeoCoordinates $coordinates): ?GeoCoordinates
    {
        $cacheIdentifier = $coordinates->latitude . '-' . $coordinates->longitude;
        if (!isset($this->enrichedCoordinates[$cacheIdentifier])) {
            try {
                $this->enrichedCoordinates[$cacheIdentifier] = $this->geoCoder->enrichGeoCoordinates($coordinates);
                $this->cache->set('enrichedCoordinates', $this->enrichedCoordinates);
            } catch (CoordinatesCouldNotBeResolved $exception) {
                $this->systemLogger->log(LogLevel::ERROR, $exception->getMessage());
                return null;
            }
        }

        return $this->enrichedCoordinates[$cacheIdentifier];
    }
}
