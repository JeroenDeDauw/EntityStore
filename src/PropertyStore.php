<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyStore {

	private $connection;
	private $tableName;

	/**
	 * This constructor is package private. Construction is done via EntityStoreFactory.
	 *
	 * @param Connection $connection
	 * @param string $tableName
	 */
	public function __construct( Connection $connection, $tableName ) {
		$this->connection = $connection;
		$this->tableName = $tableName;
	}

	/**
	 * @param PropertyRow $propertyRow
	 *
	 * @throws EntityStoreException
	 */
	public function storePropertyRow( PropertyRow $propertyRow ) {
		try {
			$this->connection->insert(
				$this->tableName,
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
		)->from( $this->tableName, 't' );
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
		)->from( $this->tableName, 't' );
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

}