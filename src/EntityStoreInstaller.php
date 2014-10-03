<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreInstaller {

	private $schemaManager;
	private $config;

	public function __construct( AbstractSchemaManager $schemaManager, EntityStoreConfig $config ) {
		$this->schemaManager = $schemaManager;
		$this->config = $config;
	}

	/**
	 * @throws DBALException
	 */
	public function install() {
		$this->schemaManager->createTable( $this->newItemTable() );
		$this->schemaManager->createTable( $this->newPropertyTable() );
	}

	private function newItemTable() {
		$table = new Table( $this->config->getItemTableName() );

		$table->addColumn( 'item_id', Type::BIGINT );
		$table->addColumn( 'item_type', Type::BIGINT, [ 'notnull' => false ] );
		$table->addColumn( 'page_title', Type::STRING, [ 'length' => 255 ] );
		$table->addColumn( 'revision_id', Type::BIGINT );
		$table->addColumn( 'revision_time', Type::STRING, [ 'length' => 25 ] );
		$table->addColumn( 'item_json', Type::BLOB );
		$table->addColumn( 'item_label_en', Type::STRING, [ 'length' => 255, 'notnull' => false ] );
		$table->addColumn( 'wp_title_en', Type::STRING, [ 'length' => 255, 'notnull' => false ] );

		$table->addIndex( [ 'item_id' ] );
		$table->addIndex( [ 'item_type' ] );
		$table->addIndex( [ 'page_title' ] );
		$table->addIndex( [ 'revision_id' ] );
		$table->addIndex( [ 'revision_time' ] );
		$table->addIndex( [ 'wp_title_en' ] );

		return $table;
	}

	private function newPropertyTable() {
		$table = new Table( $this->config->getPropertyTableName() );

		$table->addColumn( 'property_id', Type::BIGINT );
		$table->addColumn( 'property_json', Type::BLOB );
		$table->addColumn( 'page_title', Type::STRING, [ 'length' => 255 ] );
		$table->addColumn( 'revision_id', Type::BIGINT );
		$table->addColumn( 'revision_time', Type::STRING, [ 'length' => 25 ] );
		$table->addColumn( 'property_type', Type::STRING, [ 'length' => 30 ] );

		$table->addIndex( [ 'property_id' ] );
		$table->addIndex( [ 'page_title' ] );
		$table->addIndex( [ 'revision_id' ] );
		$table->addIndex( [ 'revision_time' ] );
		$table->addIndex( [ 'property_type' ] );

		return $table;
	}

	public function uninstall() {
		$this->schemaManager->dropTable( $this->config->getItemTableName() );
		$this->schemaManager->dropTable( $this->config->getPropertyTableName() );
	}

}