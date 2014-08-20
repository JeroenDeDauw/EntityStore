<?php

namespace Queryr\EntityStore\Data;

/**
 * Value object representing a row in the items table.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemRow {

	private $itemJson;
	private $itemInfo;

	/**
	 * @param string $itemJson
	 * @param ItemInfo $info
	 */
	public function __construct( $itemJson, ItemInfo $info ) {
		$this->itemJson = $itemJson;
		$this->itemInfo = $info;
	}

	/**
	 * @return string
	 */
	public function getItemJson() {
		return $this->itemJson;
	}

	/**
	 * @return int
	 */
	public function getNumericItemId() {
		return $this->itemInfo->getNumericItemId();
	}

	/**
	 * @return string
	 */
	public function getPageTitle() {
		return $this->itemInfo->getPageTitle();
	}

	/**
	 * @return int
	 */
	public function getRevisionId() {
		return $this->itemInfo->getRevisionId();
	}

	/**
	 * @return string
	 */
	public function getRevisionTime() {
		return $this->itemInfo->getRevisionTime();
	}

}
