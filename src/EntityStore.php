<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;

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

	/**
	 * @param ItemRow $itemRow
	 *
	 * @throws EntityStoreException
	 */
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

	/**
	 * @param PropertyRow $propertyRow
	 *
	 * @throws EntityStoreException
	 */
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
			$row['item_json'],
			$this->newItemInfoFromResultRow( $row )
		);
	}

	private function newItemInfoFromResultRow( array $row ) {
		return new ItemInfo(
			$row['item_id'],
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
			$row['property_json'],
			$this->newPropertyInfoFromResultRow( $row )
		);
	}

	private function newPropertyInfoFromResultRow( array $row ) {
		return new PropertyInfo(
			$row['property_id'],
			$row['page_title'],
			$row['revision_id'],
			$row['revision_time'],
			$row['property_type']
		);
	}

	private function selectPropertyInfoSets() {
		return $this->connection->createQueryBuilder()->select(
			't.property_id',
			't.page_title',
			't.revision_id',
			't.revision_time',
			't.property_type'
		)->from( $this->config->getPropertyTableName(), 't' );
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return PropertyInfo[]
	 * @throws EntityStoreException
	 */
	public function getPropertyInfo( $limit, $offset ) {
		try {
			$rows = $this->selectPropertyInfoSets()
				->orderBy( 't.property_id', 'asc' )
				->setMaxResults( $limit )
				->setFirstResult( $offset )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

		return $this->newPropertyInfoArrayFromResult( $rows );
	}

	private function newPropertyInfoArrayFromResult( \Traversable $rows ) {
		$infoList = [];

		foreach ( $rows as $resultRow ) {
			$infoList[] = $this->newPropertyInfoFromResultRow( $resultRow );
		}

		return $infoList;
	}

	private function selectItemInfoSets() {
		return $this->connection->createQueryBuilder()->select(
			't.item_id',
			't.page_title',
			't.revision_id',
			't.revision_time'
		)->from( $this->config->getItemTableName(), 't' );
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return ItemInfo[]
	 * @throws EntityStoreException
	 */
	public function getItemInfo( $limit, $offset ) {
		try {
			$rows = $this->selectItemInfoSets()
				->orderBy( 't.item_id', 'asc' )
				->setMaxResults( $limit )
				->setFirstResult( $offset )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

		return $this->newItemInfoArrayFromResult( $rows );
	}

	private function newItemInfoArrayFromResult( \Traversable $rows ) {
		$infoList = [];

		foreach ( $rows as $resultRow ) {
			$infoList[] = $this->newItemInfoFromResultRow( $resultRow );
		}

		return $infoList;
	}

}