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

### Version 0.5.3 (2014-09-06)

* Added `InstanceOfTypeExtractor` implementation of `ItemTypeExtractor`

### Version 0.5.2 (2014-09-06)

* Fixed item serialization bug in `ItemRowFactory`

### Version 0.5.1 (2014-09-06)

* Added `ItemStore::getItemTypes`

### Version 0.5 (2014-09-06)

* Removed the constructors of `ItemRow` and `ItemInfo`
* Added `item type` and `english label` fields to the items table
* Added `ItemRow::getItemInfo`
* Added `EntityPageInfo`
* Added `ItemRowFactory`. Construction of `ItemRow` should now be done via this class

### Version 0.4 (2014-08-27)

* Added `EntityStoreFactory`
* Construction of `EntityStore` is now package private
* Added `ItemStore` and `PropertyStore`, both can be constructed via `EntityStoreFactory`
* Added `PropertyTypeLookup`, which can be constructed via `EntityStoreFactory::newPropertyTypeLookup`

### Version 0.3.1 (2014-08-20)

* Added extra method level docs for better type hinting

### Version 0.3 (2014-08-20)

* `ItemRow` and `PropertyRow` are now in `Queryr\EntityStore\Rows`
* Changed the constructor signatures of `ItemRow` and `PropertyRow`
* All `EntityStore` methods now throw exceptions of type `EntityStoreException`
* Added `EntityStoreException`
* Added `PropertyInfo` and `ItemInfo`
* Added `getPropertyInfo` and `getItemInfo` to `EntityStore`

### Version 0.2 (2014-06-29)

* Renamed package from `queryr/dump-store` to `queryr/entity-store`
* Renamed `Store` class to `EntityStore`
* Renamed `StoreInstaller` class to `EntityStoreInstaller`
* `EntityStore` now requires an instance of `EntityStoreConfig` in its constructor
* `EntityStoreInstaller` now requires an instance of `EntityStoreConfig` in its constructor

### Version 0.1 (2014-05-15)

* Initial release
