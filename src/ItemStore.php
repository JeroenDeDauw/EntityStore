<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\ItemRow;
use Wikibase\DataModel\Entity\ItemId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemStore {

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
	 * @param ItemRow $itemRow
	 *
	 * @throws EntityStoreException
	 */
	public function storeItemRow( ItemRow $itemRow ) {
		$this->deleteItemById( ItemId::newFromNumber( $itemRow->getNumericItemId() ) );

		try {
			$this->connection->insert(
				$this->tableName,
				[
					'item_id' => $itemRow->getNumericItemId(),
					'item_type' => $itemRow->getItemType(),
					'item_label_en' => $itemRow->getEnglishLabel(),

					'page_title' => $itemRow->getPageTitle(),
					'revision_id' => $itemRow->getRevisionId(),
					'revision_time' => $itemRow->getRevisionTime(),

					'item_json' => $itemRow->getItemJson(),
				]
			);
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}
	}

	/**
	 * @param ItemId $itemId
	 *
	 * @throws EntityStoreException
	 */
	public function deleteItemById( ItemId $itemId ) {
		try {
			$this->connection->delete(
				$this->tableName,
				[
					'item_id' => $itemId->getNumericId()
				]
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
			't.revision_time',
			't.item_type',
			't.item_label_en'
		)->from( $this->tableName, 't' );
	}

	private function newItemRowFromResult( \Traversable $rows ) {
		$rows = iterator_to_array( $rows );

		if ( count( $rows ) < 1 ) {
			return null;
		}

		$row = reset( $rows );

		return ( new ItemRow() )
			->setItemJson( $row['item_json'] )
			->setNumericItemId( $row['item_id'] )
			->setPageTitle( $row['page_title'] )
			->setRevisionId( $row['revision_id'] )
			->setRevisionTime( $row['revision_time'] )
			->setItemType( $row['item_type'] )
			->setEnglishLabel( $row['item_label_en'] );
	}

	private function newItemInfoFromResultRow( array $row ) {
		return ( new ItemInfo() )
			->setNumericItemId( $row['item_id'] )
			->setPageTitle( $row['page_title'] )
			->setRevisionId( $row['revision_id'] )
			->setRevisionTime( $row['revision_time'] )
			->setItemType( $row['item_type'] )
			->setEnglishLabel( $row['item_label_en'] );
	}

	private function selectItemInfoSets() {
		return $this->connection->createQueryBuilder()->select(
			't.item_id',
			't.page_title',
			't.revision_id',
			't.revision_time',
			't.item_type',
			't.item_label_en'
		)->from( $this->tableName, 't' );
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @param int|null $itemType
	 *
	 * @return ItemInfo[]
	 * @throws EntityStoreException
	 */
	public function getItemInfo( $limit, $offset, $itemType = null ) {
		$query = $this->selectItemInfoSets()
			->orderBy( 't.item_id', 'asc' )
			->setMaxResults( $limit )
			->setFirstResult( $offset );

		if ( is_int( $itemType ) ) {
			$query->where( 't.item_type = ?' )->setParameter( 0, $itemType );
		}

		try {
			$rows = $query->execute();
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

	/**
	 * @param int $limit
	 * @param int $offset
	 *
	 * @return int[]
	 * @throws EntityStoreException
	 */
	public function getItemTypes( $limit = 100, $offset = 0 ) {
		try {
			$rows = $this->connection->createQueryBuilder()
				->select( 'DISTINCT t.item_type' )
				->from( $this->tableName, 't' )
				->where( 't.item_type IS NOT NULL' )
				->orderBy( 't.item_type', 'ASC' )
				->setMaxResults( $limit )
				->setFirstResult( $offset )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

		return $this->getTypeArrayFromRows( $rows );
	}

	private function getTypeArrayFromRows( \Traversable $rows ) {
		$types = [];

		foreach ( $rows as $row ) {
			$types[] = (int)$row['item_type'];
		}

		return $types;
	}

}