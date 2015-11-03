<?php

namespace Tests\Queryr\EntityStore\Fixtures;

use Doctrine\DBAL\DriverManager;

class TestFixtureFactory {

	public static function newInstance() {
		return new self();
	}

	public function newConnection() {
		return DriverManager::getConnection( array(
			'driver' => 'pdo_sqlite',
			'memory' => true,
		) );
	}

}