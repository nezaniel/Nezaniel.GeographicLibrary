<?php
namespace Nezaniel\GeographicLibrary\Domain\Exception;

/*                                                                               *
 * This script belongs to the Neos Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               */
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Exception;

/**
 * An exception to be thrown if no coordinates can be found for the given parameters
 */
class NoSuchCoordinatesException extends Exception
{
}
