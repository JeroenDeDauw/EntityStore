<?php

namespace Tests\Wikibase\Dump\Store\Fixtures;

use PDO;

class TestFixtureFactory {

	const DB_NAME = 'dump_store_tests';

	public static function newInstance() {
		return new self();
	}

	public function newPDO() {
		return new PDO(
			'mysql:dbname=' . self::DB_NAME . ';host=localhost',
			'dstore_tester',
			'mysql_is_evil'
		);
	}

}