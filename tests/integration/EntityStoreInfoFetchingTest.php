<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\ItemInfo;
use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\Data\ItemRow;
use Queryr\EntityStore\EntityStore;

/**
 * @covers Queryr\EntityStore\EntityStore
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreInfoFetchingTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var EntityStore
	 */
	private $store;

	/**
	 * @var ItemInfo[]
	 */
	private $itemInfos = [];

	/**
	 * @var PropertyInfo[]
	 */
	private $propertyInfos = [];

	public function setUp() {
		$this->createStore();
		$this->insertPropertyRows();
		$this->insertItemRows();
	}

	private function createStore() {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		$installer = new EntityStoreInstaller( $connection->getSchemaManager(), $config );
		$installer->install();

		$this->store = ( new EntityStoreFactory( $connection, $config ) )->newEntityStore();
	}

	private function insertItemRows() {
		foreach ( [ 1, 10, 2, 9, 8, 7, 3, 4, 6, 5 ] as $integer ) {
			$info = new ItemInfo(
				$integer,
				'Item:Q' . $integer,
				'424242' . $integer,
				'2014-02-27T11:40:' . $integer . 'Z',
				$integer + 1,
				'item ' . $integer
			);

			$this->itemInfos[$integer] = $info;

			$this->store->storeItemRow(
				new ItemRow(
					'json be here ' . $integer,
					$info
				)
			);
		}

		sort( $this->itemInfos );
	}

	private function insertPropertyRows() {
		foreach ( [ 101, 110, 102, 109, 108, 107, 103, 104, 106, 105 ] as $integer ) {
			$info = new PropertyInfo(
				$integer,
				'Property:P' . $integer,
				'424242' . $integer,
				$integer . '4-02-27T11:40:12Z',
				'string'
			);

			$this->propertyInfos[$integer] = $info;

			$this->store->storePropertyRow(
				new PropertyRow(
					'json be here ' . $integer,
					$info
				)
			);
		}

		sort( $this->propertyInfos );
	}

	public function testGivenLimitBiggerThanSet_getPropertyInfoReturnsAllRows() {
		$propertyInfoSets = $this->store->getPropertyInfo( 100, 0 );

		$this->assertIsPropertyInfoArray( $propertyInfoSets );
		$this->assertEquals(
			array_values( $this->propertyInfos ),
			$propertyInfoSets
		);
	}

	public function testGivenLimitSmallerThanSet_getPropertyInfoReturnsLimitedRows() {
		$propertyInfoSets = $this->store->getPropertyInfo( 5, 0 );

		$this->assertIsPropertyInfoArray( $propertyInfoSets );
		$this->assertEquals(
			array_values( array_slice(  $this->propertyInfos, 0, 5 ) ),
			$propertyInfoSets
		);
	}

	public function testGivenOffsetSmallerThanSet_getPropertyInfoReturnsPagedRows() {
		$propertyInfoSets = $this->store->getPropertyInfo( 5, 7 );

		$this->assertIsPropertyInfoArray( $propertyInfoSets );
		$this->assertEquals(
			array_values( array_slice(  $this->propertyInfos, 7, 5 ) ),
			$propertyInfoSets
		);
	}

	public function testGivenOffsetBiggerThanSet_getPropertyInfoReturnsEmptyArray() {
		$propertyInfoSets = $this->store->getPropertyInfo( 5, 20 );

		$this->assertIsPropertyInfoArray( $propertyInfoSets );
		$this->assertEquals(
			[],
			$propertyInfoSets
		);
	}

	private function assertIsPropertyInfoArray( $info ) {
		$this->assertInternalType( 'array', $info );
		$this->assertContainsOnlyInstancesOf( 'Queryr\EntityStore\Data\PropertyInfo', $info );
	}

	public function testGivenLimitBiggerThanSet_getItemInfoReturnsAllRows() {
		$itemInfoSets = $this->store->getItemInfo( 100, 0 );

		$this->assertIsItemInfoArray( $itemInfoSets );
		$this->assertEquals(
			array_values( $this->itemInfos ),
			$itemInfoSets
		);
	}

	public function testGivenLimitSmallerThanSet_getItemInfoReturnsLimitedRows() {
		$itemInfoSets = $this->store->getItemInfo( 5, 0 );

		$this->assertIsItemInfoArray( $itemInfoSets );
		$this->assertEquals(
			array_values( array_slice(  $this->itemInfos, 0, 5 ) ),
			$itemInfoSets
		);
	}

	public function testGivenOffsetSmallerThanSet_getItemInfoReturnsPagedRows() {
		$itemInfoSets = $this->store->getItemInfo( 5, 7 );

		$this->assertIsItemInfoArray( $itemInfoSets );
		$this->assertEquals(
			array_values( array_slice(  $this->itemInfos, 7, 5 ) ),
			$itemInfoSets
		);
	}

	public function testGivenOffsetBiggerThanSet_getItemInfoReturnsEmptyArray() {
		$itemInfoSets = $this->store->getItemInfo( 5, 20 );

		$this->assertIsItemInfoArray( $itemInfoSets );
		$this->assertEquals(
			[],
			$itemInfoSets
		);
	}

	private function assertIsItemInfoArray( $info ) {
		$this->assertInternalType( 'array', $info );
		$this->assertContainsOnlyInstancesOf( 'Queryr\EntityStore\Data\ItemInfo', $info );
	}

}
