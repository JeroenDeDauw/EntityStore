<?php

namespace Queryr\EntityStore;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class EntityStoreException extends \RuntimeException {

	public function __construct( $message, \Exception $previous = null ) {
		parent::__construct( $message, 0, $previous );
	}

}