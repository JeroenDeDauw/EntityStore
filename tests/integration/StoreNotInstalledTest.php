<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\EntityStoreConfig;
use Queryr\EntityStore\EntityStoreFactory;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Wikibase\DataModel\Entity\PropertyId;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StoreNotInstalledTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var EntityStoreFactory
	 */
	private $factory;

	public function setUp() {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$config = new EntityStoreConfig();

		$this->factory = new EntityStoreFactory( $connection, $config );
	}

	public function testPropertyTypeLookupThrowsExceptionWhenStoreNotInstalled() {
		$this->setExpectedException( 'Queryr\EntityStore\PropertyTypeLookupException' );
		$this->factory->newPropertyTypeLookup()->getTypeOfProperty( new PropertyId( 'P2'  ) );
	}

}
