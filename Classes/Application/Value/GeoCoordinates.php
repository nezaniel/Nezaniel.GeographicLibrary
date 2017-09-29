<?php

namespace Nezaniel\GeographicLibrary\Application\Value;

/*                                                                              *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              */
use Neos\Flow\Annotations as Flow;

/**
 * The GeoCoordinates application value object
 *
 * @see http://schema.org/GeoCoordinates
 */
final class GeoCoordinates implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @var float
     */
    protected $elevation;

    /**
     * @var float
     */
    protected $latitude;

    /**
     * @var float
     */
    protected $longitude;

    /**
     * @var string
     */
    protected $postalCode;

    /**
     * @var CountryCode
     */
    protected $addressCountry;


    public function __construct(float $latitude, float $longitude, float $elevation = null, string $address = null, string $postalCode = null, CountryCode $addressCountry = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->elevation = $elevation;
        $this->address = $address;
        $this->postalCode = $postalCode;
        $this->addressCountry = $addressCountry;
    }


    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return float|null
     */
    public function getElevation()
    {
        return $this->elevation;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @return string|null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @return CountryCode|null
     */
    public function getAddressCountry()
    {
        return $this->addressCountry;
    }


    /**
     * Calculates the distance to another point on the surface of a sphere
     *
     * @param GeoCoordinates $other
     * @return float The calculated distance in km
     * @link Wikipedia <https://en.wikipedia.org/wiki/Sphere>
     */
    public function getDistance(GeoCoordinates $other): float
    {
        return (6370
            * acos(
                sin(deg2rad($this->latitude))
                * sin(deg2rad($other->getLatitude()))
                + cos(deg2rad($this->latitude))
                * cos(deg2rad($other->getLatitude()))
                * cos(deg2rad($this->longitude) - deg2rad($other->getLongitude()))
            )
        );
    }


    public function toArray(): array
    {
        $result = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
        if ($this->elevation) {
            $result['elevation'] = $this->elevation;
        }
        if ($this->address) {
            $result['address'] = $this->address;
        }
        if ($this->postalCode) {
            $result['postalCode'] = $this->postalCode;
        }
        if ($this->addressCountry) {
            $result['addressCountry'] = $this->addressCountry;
        }

        return $result;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
