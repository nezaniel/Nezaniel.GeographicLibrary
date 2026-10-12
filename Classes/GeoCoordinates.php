<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

use Neos\Flow\Annotations as Flow;

/**
 * @see http://schema.org/GeoCoordinates
 */
#[Flow\Proxy(false)]
final readonly class GeoCoordinates implements \JsonSerializable
{
    public function __construct(
        public float $latitude,
        public float $longitude,
        public ?float $elevation = null,
        public ?string $address = null,
        public ?string $postalCode = null,
        public ?CountryCode $addressCountry = null,
    ) {
    }

    /**
     * @param array<string,mixed> $values
     */
    public static function fromArray(array $values): self
    {
        $latitude = $values['latitude'] ?? null;
        if (!is_float($latitude)) {
            throw new \InvalidArgumentException('latitude must be of type float, ' . get_debug_type($latitude) . 'given');
        }
        $longitude = $values['longitude'] ?? null;
        if (!is_float($longitude)) {
            throw new \InvalidArgumentException('longitude must be of type float, ' . get_debug_type($longitude) . 'given');
        }
        $elevation = $values['elevation'] ?? null;
        if (!is_null($elevation) && !is_float($elevation)) {
            throw new \InvalidArgumentException('elevation must be null or of type float, ' . get_debug_type($elevation) . 'given');
        }
        $address = $values['address'] ?? null;
        if (!is_null($address) && !is_string($address)) {
            throw new \InvalidArgumentException('address must be null or of type string, ' . get_debug_type($address) . 'given');
        }
        $postalCode = $values['postalCode'] ?? null;
        if (!is_null($postalCode) && !is_string($postalCode)) {
            throw new \InvalidArgumentException('postal code must be null or of type string, ' . get_debug_type($postalCode) . 'given');
        }
        $addressCountry = $values['addressCountry'] ?? null;
        if (!$addressCountry instanceof CountryCode) {
            if (is_array($addressCountry) && array_keys($addressCountry) === ['value'] && is_string($addressCountry['value'])) {
                $addressCountry = CountryCode::fromString($addressCountry['value']);
            } elseif (is_string($addressCountry)) {
                $addressCountry = CountryCode::fromString($addressCountry);
            } elseif (!is_null($addressCountry)) {
                throw new \InvalidArgumentException('address country must be null or of type string or CountryCode, ' . get_debug_type($addressCountry) . 'given');
            }
        }

        return new self(
            latitude: $latitude,
            longitude: $longitude,
            elevation: $elevation,
            address: $address,
            postalCode: $postalCode,
            addressCountry: $addressCountry,
        );
    }

    /**
     * Calculates the distance to another point on the surface of a sphere
     *
     * @link Wikipedia <https://en.wikipedia.org/wiki/Sphere>
     */
    public function calculateSphereDistance(GeoCoordinates $other): float
    {
        return (6370
            * acos(
                sin(deg2rad($this->latitude))
                * sin(deg2rad($other->latitude))
                + cos(deg2rad($this->latitude))
                * cos(deg2rad($other->latitude))
                * cos(deg2rad($this->longitude) - deg2rad($other->longitude))
            )
        );
    }

    /**
     * @return array{latitude: float, longitude: float, elevation?: float, address?: string, postalCode?: string, addressCountry?: CountryCode}
     */
    public function toArray(): array
    {
        return array_filter(
            get_object_vars($this),
            fn ($value) => $value !== null
        );
    }

    /**
     * @return array{latitude: float, longitude: float, elevation?: float, address?: string, postalCode?: string, addressCountry?: CountryCode}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
