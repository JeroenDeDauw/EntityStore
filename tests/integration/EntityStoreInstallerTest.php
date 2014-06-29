<?php

namespace Tests\Queryr\EntityStore;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Queryr\EntityStore\EntityStoreConfig;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\EntityStoreInstaller;

/**
 * @covers Queryr\EntityStore\EntityStoreInstaller
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreInstallerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var EntityStoreInstaller
	 */
	private $storeInstaller;

	/**
	 * @var AbstractSchemaManager
	 */
	private $schemaManager;

	public function setUp() {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$this->schemaManager = $connection->getSchemaManager();

		$this->storeInstaller = new EntityStoreInstaller(
			$this->schemaManager,
			new EntityStoreConfig( 'kittens_' )
		);
	}

	public function testInstallationAndRemoval() {
		$this->storeInstaller->install();

		$this->assertTrue( $this->schemaManager->tablesExist( 'kittens_items' ) );
		$this->assertTrue( $this->schemaManager->tablesExist( 'kittens_properties' ) );

		$this->storeInstaller->uninstall();

		$this->assertFalse( $this->schemaManager->tablesExist( 'kittens_items' ) );
		$this->assertFalse( $this->schemaManager->tablesExist( 'kittens_properties' ) );
	}

	public function testStoresPage() {
		$this->storeInstaller->install();
	}

}
