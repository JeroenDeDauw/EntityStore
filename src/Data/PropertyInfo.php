<?php

namespace Queryr\EntityStore\Data;

/**
 * Value object representing the info stored for a property in the properties table.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyInfo {

	private $propertyId;
	private $pageTitle;
	private $revisionId;
	private $revisionTime;
	private $propertyType;

	/**
	 * @param string|int $numericPropertyId
	 * @param string $pageTitle
	 * @param string|int $revisionId
	 * @param string $revisionTime
	 * @param string $propertyType
	 */
	public function __construct( $numericPropertyId, $pageTitle, $revisionId, $revisionTime, $propertyType ) {
		$this->propertyId = (int)$numericPropertyId;
		$this->pageTitle = $pageTitle;
		$this->revisionId = (int)$revisionId;
		$this->revisionTime = $revisionTime;
		$this->propertyType = $propertyType;
	}

	/**
	 * @return int
	 */
	public function getNumericPropertyId() {
		return $this->propertyId;
	}

	/**
	 * @return string
	 */
	public function getPageTitle() {
		return $this->pageTitle;
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->revisionId;
	}

	/**
	 * @return string
	 */
	public function getRevisionTime() {
		return $this->revisionTime;
	}

	/**
	 * @return string
	 */
	public function getPropertyType() {
		return $this->propertyType;
	}

}