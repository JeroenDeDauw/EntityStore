<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\EntityStore\ItemRowFactory;
use Queryr\EntityStore\ItemStore;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Wikibase\DataModel\Entity\Item;

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
		$this->itemRow = ( new ItemRow() )
			->setPageTitle( 'Item:Q1337' )
			->setRevisionId( '424242' )
			->setItemType( 1 )
			->setRevisionTime( '2014-02-27T11:40:12Z' )
			->setEnglishLabel( 'kittens' )
			->setItemJson( 'json be here' )
			->setNumericItemId( self::ITEM_ID );
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
		$this->assertSame( $this->itemRow->getItemType(), $newItemRow->getItemType() );
		$this->assertSame( $this->itemRow->getEnglishLabel(), $newItemRow->getEnglishLabel() );
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

	public function testWhenStoreNotInitialized_getItemTypesThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getItemTypes();
	}

	public function testWhenNoItemsInStore_getItemTypesReturnsEmptyArray() {
		$this->createStore( self::AND_INSTALL );
		$this->assertSame( [], $this->store->getItemTypes() );
	}

	private function insertFiveItems() {
		$this->store->storeItemRow(
			( new ItemRow() )
				->setPageTitle( 'Item:Q1000' )
				->setRevisionId( '123456' )
				->setItemType( 5 )
				->setRevisionTime( '2014-02-27T11:40:12Z' )
				->setEnglishLabel( 'kittens' )
				->setItemJson( 'json be here' )
				->setNumericItemId( 1000 )
		);

		$this->store->storeItemRow(
			( new ItemRow() )
				->setPageTitle( 'Item:Q2000' )
				->setRevisionId( '234567' )
				->setItemType( 5 )
				->setRevisionTime( '2014-02-27T11:40:12Z' )
				->setEnglishLabel( 'cats' )
				->setItemJson( 'json be here' )
				->setNumericItemId( 2000 )
		);

		$this->store->storeItemRow(
			( new ItemRow() )
				->setPageTitle( 'Item:Q3000' )
				->setRevisionId( '345678' )
				->setItemType( 1 )
				->setRevisionTime( '2014-02-27T11:40:12Z' )
				->setEnglishLabel( 'more cats' )
				->setItemJson( 'json be here' )
				->setNumericItemId( 3000 )
		);

		$this->store->storeItemRow(
			( new ItemRow() )
				->setPageTitle( 'Item:Q4000' )
				->setRevisionId( '456789' )
				->setItemType( null )
				->setRevisionTime( '2014-02-27T11:40:12Z' )
				->setEnglishLabel( 'more kittens' )
				->setItemJson( 'json be here' )
				->setNumericItemId( 4000 )
		);

		$this->store->storeItemRow(
			( new ItemRow() )
				->setPageTitle( 'Item:Q5000' )
				->setRevisionId( '567890' )
				->setItemType( 3 )
				->setRevisionTime( '2014-02-27T11:40:12Z' )
				->setEnglishLabel( 'all kittens' )
				->setItemJson( 'json be here' )
				->setNumericItemId( 5000 )
		);
	}

	public function testGetItemTypesReturnsDistinctNonNullTypes() {
		$this->createStore( self::AND_INSTALL );
		$this->insertFiveItems();

		$this->assertSame( [ 1, 3, 5 ], $this->store->getItemTypes() );

		$this->assertSame( [ 1 ], $this->store->getItemTypes( 1, 0 ) );
		$this->assertSame( [ 3, 5 ], $this->store->getItemTypes( 10, 1 ) );
	}

	public function testGivenNullTypeFilter_getItemInfoReturnsAllItems() {
		$this->createStore( self::AND_INSTALL );
		$this->insertFiveItems();

		$this->assertCount( 5, $this->store->getItemInfo( 10, 0, null ) );
	}

	public function testGivenMatchingTypeFilter_getItemInfoReturnsOnlyMatchingItems() {
		$this->createStore( self::AND_INSTALL );
		$this->insertFiveItems();

		$itemInfo = $this->store->getItemInfo( 10, 0, 5 );

		$this->assertCount( 2, $itemInfo );
		$this->assertSame( 1000, $itemInfo[0]->getNumericItemId() );
		$this->assertSame( 2000, $itemInfo[1]->getNumericItemId() );
	}

	public function testGivenNonMatchingTypeFilter_getItemInfoReturnsEmptyArray() {
		$this->createStore( self::AND_INSTALL );
		$this->insertFiveItems();

		$this->assertSame( [], $this->store->getItemInfo( 10, 0, 1337 ) );
	}

}
