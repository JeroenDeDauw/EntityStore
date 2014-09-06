<?php

namespace Queryr\EntityStore\Data;

/**
 * Package public.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityPageInfo {

	private $pageTitle;
	private $revisionId;
	private $revisionTime;

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

}
