<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\PropertyInfo;
use Queryr\EntityStore\Data\PropertyRow;
use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Queryr\EntityStore\EntityStoreInstaller;
use Queryr\EntityStore\PropertyStore;
use Queryr\EntityStore\PropertyTypeLookup;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @covers Queryr\EntityStore\PropertyTypeLookup
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyTypeLookupTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var PropertyTypeLookup
	 */
	private $lookup;

	public function setUp() {
		$factory = $this->createFactory();

		$this->insertPropertyRows( $factory->newPropertyStore() );

		$this->lookup = $factory->newPropertyTypeLookup();
	}

	private function createFactory() {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		$installer = new EntityStoreInstaller( $connection->getSchemaManager(), $config );
		$installer->install();

		return new EntityStoreFactory( $connection, $config );
	}


	private function insertPropertyRows( PropertyStore $store ) {
		$store->storePropertyRow(
			new PropertyRow(
				'first property',
				new PropertyInfo(
					1,
					'Property:P1',
					123,
					'2014-02-27T11:40:42Z',
					'kittens'
				)
			)
		);

		$store->storePropertyRow(
			new PropertyRow(
				'second property',
				new PropertyInfo(
					2,
					'Property:P2',
					456,
					'2014-02-27T11:40:42Z',
					'cats'
				)
			)
		);
	}

	public function testGivenNotKnownProperty_nullIsReturned() {
		$this->assertNull( $this->lookup->getTypeOfProperty( new PropertyId( 'P42' ) ) );
	}

	public function testReturnsTypeOfP1() {
		$this->assertEquals( 'kittens', $this->lookup->getTypeOfProperty( new PropertyId( 'P1' ) ) );
	}

	public function testReturnsTypeOfP2() {
		$this->assertEquals( 'cats', $this->lookup->getTypeOfProperty( new PropertyId( 'P2' ) ) );
	}

}
