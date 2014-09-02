<?php

namespace Queryr\EntityStore\Data;

/**
 * Value object representing the info stored for a property in the items table.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemInfo {

	private $itemId;
	private $pageTitle;
	private $revisionId;
	private $revisionTime;
	private $itemType;
	private $enLabel;

	/**
	 * @param string|int $numericItemId
	 * @param string $pageTitle
	 * @param string|int $revisionId
	 * @param string $revisionTime
	 * @param string|int|null $itemType
	 * @param string|null $enLabel
	 */
	public function __construct( $numericItemId, $pageTitle, $revisionId, $revisionTime, $itemType, $enLabel ) {
		$this->itemId = (int)$numericItemId;
		$this->pageTitle = $pageTitle;
		$this->revisionId = (int)$revisionId;
		$this->revisionTime = $revisionTime;
		$this->itemType = $itemType === null ? null : (int)$itemType;
		$this->enLabel = $enLabel;
	}

	/**
	 * @return int
	 */
	public function getNumericItemId() {
		return $this->itemId;
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
	 * @return string|null
	 */
	public function getEnglishLabel() {
		return $this->enLabel;
	}

	/**
	 * @return int|null
	 */
	public function getItemType() {
		return $this->itemType;
	}

}