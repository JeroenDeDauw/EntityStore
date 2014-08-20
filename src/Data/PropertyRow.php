<?php

namespace Queryr\EntityStore\Data;

/**
 * Value object representing a row in the properties table.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyRow {

	private $propertyJson;
	private $propertyInfo;

	/**
	 * @param string $propertyJson
	 * @param PropertyInfo $info
	 */
	public function __construct( $propertyJson, PropertyInfo $info ) {
		$this->propertyJson = $propertyJson;
		$this->propertyInfo = $info;
	}

	/**
	 * @return string
	 */
	public function getPropertyJson() {
		return $this->propertyJson;
	}

	/**
	 * @return PropertyInfo
	 */
	public function getPropertyInfo() {
		return $this->propertyInfo;
	}

	/**
	 * @return int
	 */
	public function getNumericPropertyId() {
		return $this->propertyInfo->getNumericPropertyId();
	}

	/**
	 * @return string
	 */
	public function getPageTitle() {
		return $this->propertyInfo->getPageTitle();
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->propertyInfo->getRevisionId();
	}

	/**
	 * @return string
	 */
	public function getRevisionTime() {
		return $this->propertyInfo->getRevisionTime();
	}

	/**
	 * @return string
	 */
	public function getPropertyType() {
		return $this->propertyInfo->getPropertyType();
	}

}