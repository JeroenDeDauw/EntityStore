<?php

namespace Tests\Queryr\Dump\Store\Fixtures;

use Doctrine\DBAL\DriverManager;
use PDO;

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