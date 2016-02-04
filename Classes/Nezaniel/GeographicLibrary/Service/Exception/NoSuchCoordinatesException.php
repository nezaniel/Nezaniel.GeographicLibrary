<?php
namespace Nezaniel\GeographicLibrary\Service\Exception;

/*                                                                               *
 * This script belongs to the TYPO3 Flow package "Nezaniel.GeographicLibrary".   *
 *                                                                               *
 * It is free software; you can redistribute it and/or modify it under           *
 * the terms of the GNU General Public License, either version 3 of the          *
 * License, or (at your option) any later version.                               *
 *                                                                               *
 * The TYPO3 project - inspiring people to share!                                *
 *                                                                               */
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Exception;

/**
 * An exception to be thrown if no coordinates can be found for the given parameters
 */
class NoSuchCoordinatesException extends Exception
{
}
