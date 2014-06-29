<?php

namespace Queryr\EntityStore;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreConfig {

	private $prefix;

	public function __construct( $tablePrefix = '' ) {
		$this->prefix = $tablePrefix;
	}

	public function getItemTableName() {
		return $this->prefix . 'items';
	}

	public function getPropertyTableName() {
		return $this->prefix . 'properties';
	}

}