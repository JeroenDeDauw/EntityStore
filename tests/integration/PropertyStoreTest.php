<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\EntityStore\PropertyStore;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\EntityStore;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\EntityStore\PropertyStore
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyStoreTest extends \PHPUnit_Framework_TestCase {

	const PROPERTY_ID = '42';
	
	const AND_INSTALL = true;
	const WITHOUT_INSTALLING = false;

	/**
	 * @var PropertyStore
	 */
	private $store;

	/**
	 * @var PropertyRow
	 */
	private $propertyRow;

	public function setUp() {
		$this->createPropertyRowField();
	}

	private function createStore( $doDbInstall = false ) {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		if ( $doDbInstall ) {
			$installer = new EntityStoreInstaller( $connection->getSchemaManager(), $config );
			$installer->install();
		}

		$this->store = ( new EntityStoreFactory( $connection, $config ) )->newPropertyStore();
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

	public function testWhenStoreNotInitialized_storePropertyRowThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->storePropertyRow( $this->propertyRow );
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

	public function testInsertingExistingPropertyOverridesTheOriginalOne() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storePropertyRow( new PropertyRow(
			'json be here',
			new PropertyInfo(
				42,
				'Property:P42',
				'424242',
				'2014-02-27T11:40:12Z',
				'string'
			)
		) );

		$this->store->storePropertyRow( new PropertyRow(
			'json be here',
			new PropertyInfo(
				42,
				'Property:P42',
				'2445325',
				'2014-02-27T11:40:12Z',
				'kittens'
			)
		) );

		$infoSets = $this->store->getPropertyInfo(  10, 0 );
		$this->assertCount( 1, $infoSets );
		$this->assertSame( 'kittens', $infoSets[0]->getPropertyType() );
	}

	public function testWhenStoreNotInitialized_deleteItemByIdThrowsException() {
		$this->createStore( self::WITHOUT_INSTALLING );
		$this->setExpectedException( 'Queryr\EntityStore\EntityStoreException' );
		$this->store->deletePropertyById( new PropertyId( 'P1' ) );
	}

	public function testGivenNonExistingId_deleteItemByIdDoesNotDeleteItems() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storePropertyRow( new PropertyRow(
			'json be here',
			new PropertyInfo(
				42,
				'Property:P42',
				'424242',
				'2014-02-27T11:40:12Z',
				'string'
			)
		) );

		$this->store->deletePropertyById( new PropertyId( 'P43' ) );

		$this->assertCount( 1, $this->store->getPropertyInfo(  10, 0 ) );
	}

	public function testGivenExistingId_deleteItemByIdDeletesItem() {
		$this->createStore( self::AND_INSTALL );

		$this->store->storePropertyRow( new PropertyRow(
			'json be here',
			new PropertyInfo(
				42,
				'Property:P42',
				'424242',
				'2014-02-27T11:40:12Z',
				'string'
			)
		) );

		$this->store->deletePropertyById( new PropertyId( 'P42' ) );

		$this->assertCount( 0, $this->store->getPropertyInfo(  10, 0 ) );
	}

}
