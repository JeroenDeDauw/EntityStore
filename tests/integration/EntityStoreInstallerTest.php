<?php

namespace Tests\Queryr\EntityStore;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\EntityStore;
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
		$this->storeInstaller = new EntityStoreInstaller( $this->schemaManager  );
	}

	public function testInstallationAndRemoval() {
		$this->storeInstaller->install();

		$this->assertTrue( $this->schemaManager->tablesExist( EntityStore::ITEMS_TABLE_NAME ) );

		$this->storeInstaller->uninstall();

		$this->assertFalse( $this->schemaManager->tablesExist( EntityStore::ITEMS_TABLE_NAME ) );
	}

	public function testStoresPage() {
		$this->storeInstaller->install();
	}

}
