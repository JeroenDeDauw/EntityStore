<?php

namespace Tests\Queryr\Dump\Store;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PDO;
use Tests\Queryr\Dump\Store\Fixtures\TestFixtureFactory;
use Queryr\Dump\Store\Store;
use Queryr\Dump\Store\StoreInstaller;

/**
 * @covers Queryr\Dump\Store\StoreInstaller
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
