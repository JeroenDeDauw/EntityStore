<?php

namespace Tests\Queryr\EntityStore;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Tests\Queryr\EntityStore\Fixtures\TestFixtureFactory;
use Queryr\EntityStore\Store;
use Queryr\EntityStore\StoreInstaller;

/**
 * @covers Queryr\EntityStore\StoreInstaller
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class StoreInstallerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var StoreInstaller
	 */
	private $storeInstaller;

	/**
	 * @var AbstractSchemaManager
	 */
	private $schemaManager;

	public function setUp() {
		$connection = TestFixtureFactory::newInstance()->newConnection();
		$this->schemaManager = $connection->getSchemaManager();
		$this->storeInstaller = new StoreInstaller( $this->schemaManager  );
	}

	public function testInstallationAndRemoval() {
		$this->storeInstaller->install();

		$this->assertTrue( $this->schemaManager->tablesExist( Store::ITEMS_TABLE_NAME ) );

		$this->storeInstaller->uninstall();

		$this->assertFalse( $this->schemaManager->tablesExist( Store::ITEMS_TABLE_NAME ) );
	}

	public function testStoresPage() {
		$this->storeInstaller->install();
	}

}
