<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyTypeLookup {

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
	 * @param PropertyId $id
	 *
	 * @return string|null
	 * @throws PropertyTypeLookupException
	 */
	public function getTypeOfProperty( PropertyId $id ) {
		$query = $this->buildQuery( $id );

		try {
			$queryResult = $query->execute();
		}
		catch ( DBALException $ex ) {
			throw new PropertyTypeLookupException( $ex->getMessage(), $ex );
		}

		return $this->getTypeFromResult( $queryResult );
	}

	private function buildQuery( PropertyId $id ) {
		return $this->connection->createQueryBuilder()
			->select( 'property_type' )
			->from( $this->tableName )
			->where( 'property_id = ?' )
			->setParameter( 0, (int)$id->getNumericId() );
	}

	private function getTypeFromResult( \Traversable $rows ) {
		$rows = iterator_to_array( $rows );

		if ( count( $rows ) < 1 ) {
			return null;
		}

		return reset( $rows )['property_type'];
	}

}