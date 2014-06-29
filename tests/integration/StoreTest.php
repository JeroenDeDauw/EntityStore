<?php

namespace Tests\Queryr\EntityStore;

use PDO;
use Queryr\EntityStore\PropertyRow;
use Queryr\EntityStore\StoreInstaller;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Wikibase\Database\PDO\PDOFactory;
use Wikibase\Database\QueryInterface\QueryInterface;
use Queryr\EntityStore\ItemRow;
use Queryr\EntityStore\Store;

/**
 * @covers Queryr\EntityStore\Store
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StoreTest extends \PHPUnit_Framework_TestCase {

	const ITEM_ID = '1337';
	const PROPERTY_ID = '42';

	/**
	 * @var Store
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
		$this->createStore();
		$this->createItemRowField();
		$this->createPropertyRowField();
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
		$this->assertNull( $this->store->getItemRowByNumericItemId( '32202' ) );
	}

	public function testCanStoreAndRetrievePropertyPage() {
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
		$this->assertNull( $this->store->getPropertyRowByNumericPropertyId( '32202' ) );
	}

}
