<?php

namespace Queryr\EntityStore\Data;

/**
 * Represents the non-blob fields from a row in the items table.
 * Package private.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
trait ItemRowInfo {

	private $itemId;
	private $pageTitle;
	private $revisionId;
	private $revisionTime;
	private $itemType;
	private $enLabel;
	private $enWikiTitle;

	/**
	 * @param string|null $enLabel
	 * @return $this
	 */
	public function setEnglishLabel( $enLabel ) {
		$this->enLabel = $enLabel;
		return $this;
	}

	/**
	 * @param int|string $itemId
	 * @return $this
	 */
	public function setNumericItemId( $itemId ) {
		$this->itemId = (int)$itemId;
		return $this;
	}

	/**
	 * @param int|null $itemType
	 * @return $this
	 */
	public function setItemType( $itemType ) {
		$this->itemType = $itemType === null ? null : (int)$itemType;
		return $this;
	}

	/**
	 * @param string $pageTitle
	 * @return $this
	 */
	public function setPageTitle( $pageTitle ) {
		$this->pageTitle = $pageTitle;
		return $this;
	}

	/**
	 * @param int|string $revisionId
	 * @return $this
	 */
	public function setRevisionId( $revisionId ) {
		$this->revisionId = (int)$revisionId;
		return $this;
	}

	/**
	 * @param string $revisionTime
	 * @return $this
	 */
	public function setRevisionTime( $revisionTime ) {
		$this->revisionTime = $revisionTime;
		return $this;
	}

	/**
	 * @param string|null $enWikiTitle
	 * @return $this
	 */
	public function setEnglishWikipediaTitle( $enWikiTitle ) {
		$this->enWikiTitle = $enWikiTitle;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getEnglishLabel() {
		return $this->enLabel;
	}

	/**
	 * @return int
	 */
	public function getNumericItemId() {
		return $this->itemId;
	}

	/**
	 * @return int|null
	 */
	public function getItemType() {
		return $this->itemType;
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
	public function getEnglishWikipediaTitle() {
		return $this->enWikiTitle;
	}

}
