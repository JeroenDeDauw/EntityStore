<?php

namespace Queryr\EntityStore;

/**
 * Value object representing a row in the properties table.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyRow {

	private $propertyId;
	private $propertyJson;
	private $pageTitle;
	private $revisionId;
	private $revisionTime;
	private $propertyType;

	/**
	 * @param string|int $numericPropertyId
	 * @param string $propertyJson
	 * @param string $pageTitle
	 * @param string|int $revisionId
	 * @param string $revisionTime
	 * @param string $propertyType
	 */
	public function __construct( $numericPropertyId, $propertyJson, $pageTitle, $revisionId, $revisionTime, $propertyType ) {
		$this->propertyId = (int)$numericPropertyId;
		$this->propertyJson = $propertyJson;
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
	public function getPropertyJson() {
		return $this->propertyJson;
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