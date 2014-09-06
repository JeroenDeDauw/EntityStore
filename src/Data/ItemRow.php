<?php

namespace Queryr\EntityStore\Data;

use InvalidArgumentException;

/**
 * Value object representing a row in the items table.
 * Package public.
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemRow {
	use ItemRowInfo;

	private $itemJson;

	/**
	 * @param string $itemJson
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setItemJson( $itemJson ) {
		if ( !is_string( $itemJson ) ) {
			throw new InvalidArgumentException( '$itemJson should be a string' );
		}

		$this->itemJson = $itemJson;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getItemJson() {
		return $this->itemJson;
	}

	/**
	 * @return ItemInfo
	 */
	public function getItemInfo() {
		return ( new ItemInfo() )
			->setPageTitle( $this->getPageTitle() )
			->setNumericItemId( $this->getNumericItemId() )
			->setRevisionId( $this->getRevisionId() )
			->setItemType( $this->getItemType() )
			->setRevisionTime( $this->getRevisionTime() )
			->setEnglishLabel( $this->getEnglishLabel() );
	}

}
