<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

use Neos\Flow\Annotations as Flow;

/**
 * The GeoCoordinates application value object
 *
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
