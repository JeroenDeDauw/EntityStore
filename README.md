# Wikibase Dump Store

Provides persistence and basic lookup capabilities for collections of Wikibase entities.

## System dependencies

* PHP 5.5 or later
* php5-mysql
* php5-sqlite (only needed for running the tests)

## Running the tests

Before running the tests

    mysql --user root -p < tests/createTestDB.sql

Running the tests

    phpunit

Removing the test database

    mysql --user root -p < tests/dropTestDB.sql
