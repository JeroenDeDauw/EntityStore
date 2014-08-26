<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\EntityStore\ItemStore;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;

/**
 * @covers Queryr\EntityStore\ItemStore
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemStoreTest extends \PHPUnit_Framework_TestCase {

	const ITEM_ID = '1337';

	const AND_INSTALL = true;
	const WITHOUT_INSTALLING = false;

	/**
	 * @var ItemStore
	 */
	private $store;

	/**
	 * @var ItemRow
	 */
	private $itemRow;

	public function setUp() {
		$this->createItemRowField();
	}

	private function createStore( $doDbInstall = false ) {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		if ( $doDbInstall ) {
			$installer = new EntityStoreInstaller( $connection->getSchemaManager(), $config );
			$installer->install();
		}

		$this->store = ( new EntityStoreFactory( $connection, $config ) )->newItemStore();
	}

	private function createItemRowField() {
		$this->itemRow = new ItemRow(
			'json be here',
			new ItemInfo(
				self::ITEM_ID,
				'Item:Q1337',
				'424242',
				'2014-02-27T11:40:12Z'
			)
		);
	}

	public function testCanStoreAndRetrieveItemPage() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storeItemRow( $this->itemRow );

		$newItemRow = $this->store->getItemRowByNumericItemId( self::ITEM_ID );

		$this->assertInstanceOf( 'Queryr\EntityStore\Data\ItemRow', $newItemRow );

		$this->assertSame( $this->itemRow->getNumericItemId(), $newItemRow->getNumericItemId() );
		$this->assertSame( $this->itemRow->getItemJson(), $newItemRow->getItemJson() );
		$this->assertSame( $this->itemRow->getPageTitle(), $newItemRow->getPageTitle() );
		$this->assertSame( $this->itemRow->getRevisionId(), $newItemRow->getRevisionId() );
		$this->assertSame( $this->itemRow->getRevisionTime(), $newItemRow->getRevisionTime() );
	}

	public function testGivenNotKnownId_getItemRowByNumericItemIdReturnsNull() {
		$this->createStore( self::AND_INSTALL );
		$this->assertNull( $this->store->getItemRowByNumericItemId( '32202' ) );
	}

	public function testWhenStoreNotInitialized_storeItemRowThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->storeItemRow( $this->itemRow );
	}

	public function testWhenStoreNotInitialized_getItemRowByNumericItemIdThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getItemRowByNumericItemId( 1 );
	}

	public function testWhenStoreNotInitialized_getItemInfoThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getItemInfo( 10, 0 );
	}

}
