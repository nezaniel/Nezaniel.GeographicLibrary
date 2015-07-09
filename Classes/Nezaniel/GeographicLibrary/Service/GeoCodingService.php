<?php
namespace Nezaniel\GeographicLibrary\Service;

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
use TYPO3\Flow\Cache\Frontend\VariableFrontend;

/**
 * @Flow\Scope("singleton")
 */
class GeoCodingService {

	/**
	 * @var GeoCodingAdapterInterface
	 */
	protected $geoCodingAdapter;

	/**
	 * @var array
	 */
	protected $postalCodeCoordinates = [];

	/**
	 * @var array
	 */
	protected $addressCoordinates = [];

	/**
	 * @var VariableFrontend
	 */
	protected $cache;


	/**
	 * @param VariableFrontend $cache
	 */
	public function setCache(VariableFrontend $cache) {
		$this->cache = $cache;
	}

	/**
	 * Initialize the object and load caches
	 */
	public function initializeObject() {
		$this->postalCodeCoordinates = $this->cache->get('postalCodeCoordinates') ?: [];
		$this->addressCoordinates = $this->cache->get('addressCoordinates') ?: [];
	}


	/**
	 * @param string $address
	 * @return array The coordinates
	 */
	public function fetchCoordinatesByAddress($address) {
		if (!isset($this->addressCoordinates[$address])) {
			$this->addressCoordinates[$address] = $this->geoCodingAdapter->fetchCoordinatesByAddress($address);
			$this->cache->set('addressCoordinates', $this->addressCoordinates);
		}
		return $this->addressCoordinates[$address];
	}

	/**
	 * @param string $zip
	 * @param string $countryCode The two character ISO 3166-1 country code
	 * @return array The coordinates
	 */
	public function fetchCoordinatesByGermanPostalCode($zip, $countryCode) {
		$cacheIdentifier = $zip . '-' . $countryCode;
		if (!isset($this->postalCodeCoordinates[$cacheIdentifier])) {
			$this->postalCodeCoordinates[$cacheIdentifier] = $this->geoCodingAdapter->fetchCoordinatesByPostalCode($zip, $countryCode);
			$this->cache->set('postalCodeCoordinates', $this->postalCodeCoordinates);
		}
		return $this->postalCodeCoordinates[$cacheIdentifier];
	}

}
