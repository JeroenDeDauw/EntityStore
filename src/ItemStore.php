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
					'wp_title_en' => $itemRow->getEnglishWikipediaTitle(),

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
				->where( 'item_id = ?' )
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
			'item_id',
			'item_json',
			'page_title',
			'revision_id',
			'revision_time',
			'item_type',
			'item_label_en',
			'wp_title_en'
		)->from( $this->tableName );
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
			->setEnglishWikipediaTitle( $row['wp_title_en'] )
			->setEnglishLabel( $row['item_label_en'] );
	}

	private function newItemInfoFromResultRow( array $row ) {
		return ( new ItemInfo() )
			->setNumericItemId( $row['item_id'] )
			->setPageTitle( $row['page_title'] )
			->setRevisionId( $row['revision_id'] )
			->setRevisionTime( $row['revision_time'] )
			->setItemType( $row['item_type'] )
			->setEnglishWikipediaTitle( $row['wp_title_en'] )
			->setEnglishLabel( $row['item_label_en'] );
	}

	private function selectItemInfoSets() {
		return $this->connection->createQueryBuilder()->select(
			'item_id',
			'page_title',
			'revision_id',
			'revision_time',
			'item_type',
			'item_label_en',
			'wp_title_en'
		)->from( $this->tableName );
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
			->orderBy( 'item_id', 'asc' )
			->setMaxResults( $limit )
			->setFirstResult( $offset );

		if ( is_int( $itemType ) ) {
			$query->where( 'item_type = ?' )->setParameter( 0, $itemType );
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
				->select( 'DISTINCT item_type' )
				->from( $this->tableName )
				->where( 'item_type IS NOT NULL' )
				->orderBy( 'item_type', 'ASC' )
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

	/**
	 * @param string $pageName
	 *
	 * @return ItemId|null
	 * @throws EntityStoreException
	 */
	public function getIdForEnWikiPage( $pageName ) {
		try {
			$rows = $this->connection->createQueryBuilder()
				->select( 'item_id' )
				->from( $this->tableName )
				->where( 'wp_title_en = ?' )
				->setParameter( 0, $pageName )
				->setMaxResults( 1 )
				->execute();
		}
		catch ( DBALException $ex ) {
			throw new EntityStoreException( $ex->getMessage(), $ex );
		}

		foreach ( $rows as $row ) {
			return ItemId::newFromNumber( $row['item_id'] );
		}

		return null;
	}

}