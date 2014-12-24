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

### Version 0.6.2 (2014-12-24)

* Wikibase DataModel 1.x is no longer supported

### Version 0.6.1 (2014-10-21)

* Allow installation together with DataModel 2.x

### Version 0.6.0 (2014-10-03)

* Added `ItemStore::deleteItemById`
* Added `PropertyStore::deletePropertyById`
* Inserting an item or a property will now cause any older versions to be deleted
* The ItemStore now indexes the enwiki sitelink

### Version 0.5.4 (2014-09-08)

* Added optional `$itemType` parameter to `ItemStore::getItemInfo`

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
