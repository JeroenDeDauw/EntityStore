<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\EntityStore\EntityStore;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;

/**
 * @covers Queryr\EntityStore\EntityStore
 * @covers Queryr\EntityStore\ItemStore
 * @covers Queryr\EntityStore\PropertyStore
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreTest extends \PHPUnit_Framework_TestCase {

	const ITEM_ID = '1337';
	const PROPERTY_ID = '42';

	const AND_INSTALL = true;
	const WITHOUT_INSTALLING = false;

	/**
	 * @var EntityStore
	 */
	private $store;

	/**
	 * @var \Queryr\EntityStore\Data\ItemRow
	 */
	private $itemRow;

	/**
	 * @var PropertyRow
	 */
	private $propertyRow;

	public function setUp() {
		$this->createItemRowField();
		$this->createPropertyRowField();
	}

	private function createStore( $doDbInstall = false ) {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		if ( $doDbInstall ) {
			$installer = new EntityStoreInstaller( $connection->getSchemaManager(), $config );
			$installer->install();
		}

		$this->store = ( new EntityStoreFactory( $connection, $config ) )->newEntityStore();
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

	private function createPropertyRowField() {
		$this->propertyRow = new PropertyRow(
			'json be here',
			new PropertyInfo(
				self::PROPERTY_ID,
				'Property:P42',
				'424242',
				'2014-02-27T11:40:12Z',
				'string'
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

	public function testCanStoreAndRetrievePropertyPage() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storePropertyRow( $this->propertyRow );

		$newPropertyRow = $this->store->getPropertyRowByNumericPropertyId( self::PROPERTY_ID );

		$this->assertInstanceOf( 'Queryr\EntityStore\Data\PropertyRow', $newPropertyRow );

		$this->assertSame( $this->propertyRow->getNumericPropertyId(), $newPropertyRow->getNumericPropertyId() );
		$this->assertSame( $this->propertyRow->getPropertyJson(), $newPropertyRow->getPropertyJson() );
		$this->assertSame( $this->propertyRow->getPageTitle(), $newPropertyRow->getPageTitle() );
		$this->assertSame( $this->propertyRow->getRevisionId(), $newPropertyRow->getRevisionId() );
		$this->assertSame( $this->propertyRow->getRevisionTime(), $newPropertyRow->getRevisionTime() );
		$this->assertSame( $this->propertyRow->getPropertyType(), $newPropertyRow->getPropertyType() );
	}

	public function testGivenNotKnownId_getPropertyRowByNumericPropertyIdReturnsNull() {
		$this->createStore( self::AND_INSTALL );
		$this->assertNull( $this->store->getPropertyRowByNumericPropertyId( '32202' ) );
	}

	public function testWhenStoreNotInitialized_storeItemRowThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->storeItemRow( $this->itemRow );
	}

	public function testWhenStoreNotInitialized_storePropertyRowThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->storePropertyRow( $this->propertyRow );
	}

	public function testWhenStoreNotInitialized_getItemRowByNumericItemIdThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getItemRowByNumericItemId( 1 );
	}

	public function testWhenStoreNotInitialized_getPropertyRowByNumericPropertyIdThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getPropertyRowByNumericPropertyId( 1 );
	}

	public function testWhenStoreNotInitialized_getPropertyInfoThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getPropertyInfo( 10, 0 );
	}

	public function testWhenStoreNotInitialized_getItemInfoThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->getItemInfo( 10, 0 );
	}

}
