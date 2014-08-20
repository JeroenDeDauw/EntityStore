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

### Version 0.3 (2014-08-20)

* `ItemRow` and `PropertyRow` are now in `Queryr\EntityStore\Data\Rows`
* Added `EntityStoreException`
* All `EntityStore` methods now throw exceptions of type `EntityStoreException`

### Version 0.2 (2014-06-29)

* Renamed package from `queryr/dump-store` to `queryr/entity-store`
* Renamed `Store` class to `EntityStore`
* Renamed `StoreInstaller` class to `EntityStoreInstaller`
* `EntityStore` now requires an instance of `EntityStoreConfig` in its constructor
* `EntityStoreInstaller` now requires an instance of `EntityStoreConfig` in its constructor

### Version 0.1 (2014-05-15)

* Initial release
