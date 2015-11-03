<?php

if ( PHP_SAPI !== 'cli' ) {
	die( 'Not an entry point' );
}

error_reporting( E_ALL | E_STRICT );
ini_set( 'display_errors', 1 );

if ( !is_readable( __DIR__ . '/../vendor/autoload.php' ) ) {
	die( 'You need to install this package with Composer before you can run the tests' );
}

$autoLoader = require __DIR__ . '/../vendor/autoload.php';

$autoLoader->addPsr4( 'Tests\\Queryr\\EntityStore\\Fixtures\\', 'tests/fixtures/' );

unset( $autoLoader );