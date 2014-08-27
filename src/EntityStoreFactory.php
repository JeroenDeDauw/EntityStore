<?php

namespace Queryr\EntityStore;

use Doctrine\DBAL\Connection;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreFactory {

	private $connection;
	private $config;

	public function __construct( Connection $connection, EntityStoreConfig $config ) {
		$this->connection = $connection;
		$this->config = $config;
	}

	public function newEntityStore() {
		return new EntityStore(
			$this->newItemStore(),
			$this->newPropertyStore()
		);
	}

	public function newItemStore() {
		return new ItemStore( $this->connection, $this->config->getItemTableName() );
	}

	public function newPropertyStore() {
		return new PropertyStore( $this->connection, $this->config->getPropertyTableName() );
	}

	public function newPropertyTypeLookup() {
		return new PropertyTypeLookup( $this->connection, $this->config->getPropertyTableName() );
	}

}