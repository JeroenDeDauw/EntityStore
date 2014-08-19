<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStore {

	private $connection;
	private $config;

	public function __construct( Connection $connection, EntityStoreConfig $config ) {
		$this->connection = $connection;
		$this->config = $config;
	}

	public function storeItemRow( ItemRow $itemRow ) {
		try {
			$this->connection->insert(
				$this->config->getItemTableName(),
				array(
					'item_id' => $itemRow->getNumericItemId(),
					'item_json' => $itemRow->getItemJson(),

					'page_title' => $itemRow->getPageTitle(),
					'revision_id' => $itemRow->getRevisionId(),
					'revision_time' => $itemRow->getRevisionTime(),
				)
			);
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}
	}

	public function storePropertyRow( PropertyRow $propertyRow ) {
		try {
			$this->connection->insert(
				$this->config->getPropertyTableName(),
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
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}
	}

	/**
	 * @param string|int $numericItemId
	 * @return ItemRow|null
	 * @throws EntityStoreException
	 */
	public function getItemRowByNumericItemId( $numericItemId ) {
		try {
			$rows = $this->selectItems()
				->where( 't.item_id = ?' )
				->setParameter( 0, (int)$numericItemId )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

		return $this->newItemRowFromResult( $rows );
	}

	private function selectItems() {
		return $this->connection->createQueryBuilder()->select(
			't.item_id',
			't.item_json',
			't.page_title',
			't.revision_id',
			't.revision_time'
		)->from( $this->config->getItemTableName(), 't' );
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
	 * @throws EntityStoreException
	 */
	public function getPropertyRowByNumericPropertyId( $numericPropertyId ) {
		try {
			$rows = $this->selectProperties()
				->where( 't.property_id = ?' )
				->setParameter( 0, (int)$numericPropertyId )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

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
		)->from( $this->config->getPropertyTableName(), 't' );
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