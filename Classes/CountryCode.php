<?php

declare(strict_types=1);

namespace Nezaniel\GeographicLibrary;

use Neos\Flow\Annotations as Flow;

/**
 * The ISO 3166-1 alpha-2 country code value object
 *
 * @see https://en.wikipedia.org/wiki/ISO_3166-1
 * @todo I want to be an CLDR repository enum
 */
#[Flow\Proxy(false)]
final readonly class CountryCode implements \JsonSerializable, \Stringable
{
    public function __construct(
        public string $value
    ) {
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
