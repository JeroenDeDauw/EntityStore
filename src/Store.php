<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Store {

	const ITEMS_TABLE_NAME = 'items';
	const PROPERTIES_TABLE_NAME = 'properties';

	private $connection;

	public function __construct( Connection $connection ) {
		$this->connection = $connection;
	}

	public function storeItemRow( ItemRow $itemRow ) {
		$this->connection->insert(
			self::ITEMS_TABLE_NAME,
			array(
				'item_id' => $itemRow->getNumericItemId(),
				'item_json' => $itemRow->getItemJson(),

				'page_title' => $itemRow->getPageTitle(),
				'revision_id' => $itemRow->getRevisionId(),
				'revision_time' => $itemRow->getRevisionTime(),
			)
		);
	}

	public function storePropertyRow( PropertyRow $propertyRow ) {
		$this->connection->insert(
			self::PROPERTIES_TABLE_NAME,
			array(
				'property_id' => $propertyRow->getNumericPropertyId(),
				'property_json' => $propertyRow->getPropertyJson(),

				'page_title' => $propertyRow->getPageTitle(),
				'revision_id' => $propertyRow->getRevisionId(),
				'revision_time' => $propertyRow->getRevisionTime(),

				'property_type' => $propertyRow->getPropertyType(),
			)
		);
	}

	/**
	 * @param string|int $numericItemId
	 * @return ItemRow|null
	 */
	public function getItemRowByNumericItemId( $numericItemId ) {
		$rows = $this->selectItems()
			->where( 't.item_id = ?' )
			->setParameter( 0, (int)$numericItemId )
			->execute();

		return $this->newItemRowFromResult( $rows );
	}

	private function selectItems() {
		return $this->connection->createQueryBuilder()->select(
			't.item_id',
			't.item_json',
			't.page_title',
			't.revision_id',
			't.revision_time'
		)->from( self::ITEMS_TABLE_NAME, 't' );
	}

	private function newItemRowFromResult( \Traversable $rows ) {
		$rows = iterator_to_array( $rows );

		if ( count( $rows ) < 1 ) {
			return null;
		}

		$row = reset( $rows );

		return new ItemRow(
			$row['item_id'],
			$row['item_json'],
			$row['page_title'],
			$row['revision_id'],
			$row['revision_time']
		);
	}

	/**
	 * @param string|int $numericPropertyId
	 *
	 * @return PropertyRow|null
	 */
	public function getPropertyRowByNumericPropertyId( $numericPropertyId ) {
		$rows = $this->selectProperties()
			->where( 't.property_id = ?' )
			->setParameter( 0, (int)$numericPropertyId )
			->execute();

		return $this->newPropertyRowFromResult( $rows );
	}

	private function selectProperties() {
		return $this->connection->createQueryBuilder()->select(
			't.property_id',
			't.property_json',
			't.page_title',
			't.revision_id',
			't.revision_time',
			't.property_type'
		)->from( self::PROPERTIES_TABLE_NAME, 't' );
	}

	private function newPropertyRowFromResult( \Traversable $rows ) {
		$rows = iterator_to_array( $rows );

		if ( count( $rows ) < 1 ) {
			return null;
		}

		$row = reset( $rows );

		return new PropertyRow(
			$row['property_id'],
			$row['property_json'],
			$row['page_title'],
			$row['revision_id'],
			$row['revision_time'],
			$row['property_type']
		);
	}

}