<?php

namespace Nezaniel\GeographicLibrary\Application\Value;

/*                                                                              *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              */
use Neos\Flow\Annotations as Flow;
use Neos\Utility\Arrays;

/**
 * The Coordinates application value object
 */
final class Coordinates implements \JsonSerializable
{
    /**
     * @var double
     */
    protected $latitude;

    /**
     * @var double
     */
    protected $longitude;


    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function fromString(string $serializedCoordinates): Coordinates
    {
        list($latitude, $longitude) = Arrays::trimExplode(',', $serializedCoordinates);

        return new Coordinates($latitude, $longitude);
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
     * Calculates the distance to another point on the surface of a sphere
     *
     * @param Coordinates $other
     * @return float The calculated distance in km
     * @link Wikipedia <https://en.wikipedia.org/wiki/Sphere>
     */
    public function getDistance(Coordinates $other): float
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
        return [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
