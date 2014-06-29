# QueryR Entity Store

Provides persistence and basic lookup capabilities for collections of
[Wikibase](http://wikiba.se) entities.

## System dependencies

* PHP 5.5 or later
* php5-mysql
* php5-sqlite (only needed for running the tests)

## Running the tests

Running the tests

    phpunit

## Release notes

### Version 0.2 (dev)

* Renamed package from `queryr/dump-store` to `queryr/entity-store`
* Renamed `Store` class to `EntityStore`
* Renamed `StoreInstaller` class to `EntityStoreInstaller`

### Version 0.1 (2014-05-15)

* Initial release
