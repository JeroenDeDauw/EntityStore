<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\PropertyRow;
use Queryr\EntityStore\EntityStoreInstaller;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\ItemRow;
use Queryr\EntityStore\EntityStore;

/**
 * @covers Queryr\EntityStore\EntityStore
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
	 * @var ItemRow
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

		$this->store = new EntityStore( $connection, $config );
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

	private function createPropertyRowField() {
		$this->propertyRow = new PropertyRow(
			self::PROPERTY_ID,
			'json be here',
			'Property:P42',
			'424242',
			'2014-02-27T11:40:12Z',
			'string'
		);
	}

	public function testCanStoreAndRetrieveItemPage() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storeItemRow( $this->itemRow );

		$newItemRow = $this->store->getItemRowByNumericItemId( self::ITEM_ID );

		$this->assertInstanceOf( 'Queryr\EntityStore\ItemRow', $newItemRow );

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

		$this->assertInstanceOf( 'Queryr\EntityStore\PropertyRow', $newPropertyRow );

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



}
