<?php

namespace Nezaniel\GeographicLibrary\Application\Value;

/*                                                                              *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".  *
 *                                                                              */
use Neos\Flow\Annotations as Flow;

/**
 * The ISO 3166-1 alpha-2 country code value object
 *
 * @see https://en.wikipedia.org/wiki/ISO_3166-1
 * @todo move to CLDR repository
 */
final class CountryCode implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $countryCode;


    public function __construct(string $countryCode)
    {
        $this->countryCode = $countryCode;
    }


    public function jsonSerialize(): string
    {
        return $this->countryCode;
    }
}
