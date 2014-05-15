<?php

namespace Tests\Queryr\Dump\Store;

use PDO;
use Queryr\Dump\Store\StoreInstaller;
use Tests\Queryr\Dump\Store\Fixtures\TestFixtureFactory;
use Wikibase\Database\PDO\PDOFactory;
use Wikibase\Database\QueryInterface\QueryInterface;
use Queryr\Dump\Store\ItemRow;
use Queryr\Dump\Store\Store;

/**
 * @covers Queryr\Dump\Store\Store
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StoreTest extends \PHPUnit_Framework_TestCase {

	const ITEM_ID = '1337';

	/**
	 * @var Store
	 */
	private $store;

	/**
	 * @var ItemRow
	 */
	private $itemRow;

	public function setUp() {
		$this->createStore();
		$this->createItemRowField();
	}

	private function createStore() {
		$connection = TestFixtureFactory::newInstance()->newConnection();

		$installer = new StoreInstaller( $connection->getSchemaManager() );
		$installer->install();

		$this->store = new Store( $connection );
	}

	private function createItemRowField() {
		$this->itemRow = new ItemRow(
			self::ITEM_ID,
			'json be here',
			'Item:Q1337',
			'424242',
			'2014-02-27T11:40:12Z'
		);
	}

	public function testStoresPage() {
		$this->store->storeItemRow( $this->itemRow );

		/**
		 * @var ItemRow $newItemRow
		 */
		$newItemRow = $this->store->getItemRowByNumericItemId( self::ITEM_ID );

		$this->assertInstanceOf( 'Queryr\Dump\Store\ItemRow', $newItemRow );

		$this->assertSame( $this->itemRow->getNumericItemId(), $newItemRow->getNumericItemId() );
		$this->assertSame( $this->itemRow->getItemJson(), $newItemRow->getItemJson() );
		$this->assertSame( $this->itemRow->getPageTitle(), $newItemRow->getPageTitle() );
		$this->assertSame( $this->itemRow->getRevisionId(), $newItemRow->getRevisionId() );
		$this->assertSame( $this->itemRow->getRevisionTime(), $newItemRow->getRevisionTime() );
	}

	public function testGivenNotKnownId_getItemRowByNumericItemIdReturnsNull() {
		$this->assertNull( $this->store->getItemRowByNumericItemId( '32202' ) );
	}

}
