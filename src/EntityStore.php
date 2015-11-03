<?php

namespace Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStore {

	private $itemStore;
	private $propertyStore;

	/**
	 * This constructor is package private. Construction is done via EntityStoreFactory.
	 *
	 * @param ItemStore $itemStore
	 * @param PropertyStore $propertyStore
	 */
	public function __construct( ItemStore $itemStore, PropertyStore $propertyStore ) {
		$this->itemStore = $itemStore;
		$this->propertyStore = $propertyStore;
	}

	/**
	 * @param ItemRow $itemRow
	 *
	 * @throws EntityStoreException
	 */
	public function storeItemRow( ItemRow $itemRow ) {
		$this->itemStore->storeItemRow( $itemRow );
	}

	/**
	 * @param PropertyRow $propertyRow
	 *
	 * @throws EntityStoreException
	 */
	public function storePropertyRow( PropertyRow $propertyRow ) {
		$this->propertyStore->storePropertyRow( $propertyRow );
	}

	/**
	 * @param string|int $numericItemId
	 * @return ItemRow|null
	 * @throws EntityStoreException
	 */
	public function getItemRowByNumericItemId( $numericItemId ) {
		return $this->itemStore->getItemRowByNumericItemId( $numericItemId );
	}

	/**
	 * @param string|int $numericPropertyId
	 *
	 * @return PropertyRow|null
	 * @throws EntityStoreException
	 */
	public function getPropertyRowByNumericPropertyId( $numericPropertyId ) {
		return $this->propertyStore->getPropertyRowByNumericPropertyId( $numericPropertyId );
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return PropertyInfo[]
	 * @throws EntityStoreException
	 */
	public function getPropertyInfo( $limit, $offset ) {
		return $this->propertyStore->getPropertyInfo( $limit, $offset );
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return ItemInfo[]
	 * @throws EntityStoreException
	 */
	public function getItemInfo( $limit, $offset ) {
		return $this->itemStore->getItemInfo( $limit, $offset );
	}

}