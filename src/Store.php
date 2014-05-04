<?php

namespace Queryr\Dump\Store;

use Doctrine\DBAL\Connection;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class Store {

	const ITEMS_TABLE_NAME = 'items';

	private $connection;

	public function __construct( Connection $connection ) {
		$this->connection = $connection;
	}

	/**
	 * @param ItemRow $itemRow
	 */
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

	/**
	 * @param string|int $numericItemId
	 * @return ItemRow|null
	 */
	public function getItemRowByNumericItemId( $numericItemId ) {
		$queryBuilder = $this->connection->createQueryBuilder();

		$rows = $queryBuilder->select(
			't.item_id',
			't.item_json',
			't.page_title',
			't.revision_id',
			't.revision_time'
			)
			->from( self::ITEMS_TABLE_NAME, 't' )
			->where( 't.item_id = ?' )
			->setParameter( 0, (int)$numericItemId )
			->execute();

		$rows = iterator_to_array( $rows );

		if ( count( $rows ) < 1 ) {
			return false;
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

}