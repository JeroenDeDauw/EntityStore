# QueryR EntityStore

[![Build Status](https://secure.travis-ci.org/JeroenDeDauw/EntityStore.png?branch=master)](http://travis-ci.org/JeroenDeDauw/EntityStore)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/JeroenDeDauw/EntityStore/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/JeroenDeDauw/EntityStore/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/JeroenDeDauw/EntityStore/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/JeroenDeDauw/EntityStore/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/queryr/entity-store/version.png)](https://packagist.org/packages/queryr/entity-store)
[![Download count](https://poser.pugx.org/queryr/entity-store/d/total.png)](https://packagist.org/packages/queryr/entity-store)

Provides persistence and basic lookup capabilities for collections of
[Wikibase](http://wikiba.se) entities.

## System dependencies

* PHP 5.5 or later (PHP 7 and HHVM are supported)
* php5-sqlite (only needed for running the tests)

## Installation

To add this package as a local, per-project dependency to your project, simply add a
dependency on `queryr/entity-store` to your project's `composer.json` file.
Here is a minimal example of a `composer.json` file that just defines a dependency on
EntityStore 1.x:

```js
{
    "require": {
        "queryr/entity-store": "~1.0"
    }
}
```

## Usage

All services are constructed via the `EntityStoreFactory` class:

```php
use Queryr\EntityStore\EntityStoreFactory;
$factory = new EntityStoreFactory(
	$dbalConnection,
	new EntityStoreConfig( /* optional config */ )
);
```

`$dbalConnection` is a `Connection` object from [Doctrine DBAL](https://github.com/doctrine/dbal).

### Writing values

For writing values, you will need either `ItemStore` or `PropertyStore`.

```php
$itemStore = $factory->newItemStore();
$propertyStore = $factory->newPropertyStore();
```

The main write methods are "store document" and "remove document by id".

```php
$itemStore->storeItemRow( $itemRow );
$itemStore->deleteItemById( $itemId );
```

Note that `$itemRow` is of type `ItemRow`, which is defined by this component. `ItemRow` represents
all values in a row of the items table. It does not require having a fully instantiated Wikibase
DataModel `EntityDocument` object, you just need the JSON.

Next to `ItemRow` there also is `ItemInfo`, which is identical, apart for not having the JSON.
(Internally these share code via the package private trait `ItemRowInfo`.)

### Querying values

This list is incomplete and serves mainly to give you an idea of what this library contains.
To get a full list, look at the services you can construct via the store, and their interfaces.

**Fetching an Item by id**

```php
$q42 = $itemStore->getItemRowByNumericItemId( 42 );
```

**Property data type lookup**

```php
$lookup = $factory->newPropertyTypeLookup();
$propertyType = $lookup->getTypeOfProperty( $propertyId );
```

**List item info**

Get cheaply retrievable info on the first 100 items.

```php
$itemInfoList = $itemStore->getItemInfo( 100, 0 );
```

Restrict the result to items of type "book", assuming 424242 is the numeric id of "book".

```php
$itemInfoList = $itemStore->getItemInfo( 100, 0, 424242 );
```

**List item types**

This will get you numeric item ids that represent the types of the items ("instance of") in the system.

```php
$itemTypes = $itemStore->getItemTypes();
```

## Running the tests

For tests only

    composer test

For style checks only

	composer cs

For a full CI run

	composer ci

## Release notes

### Version 1.0.0 (2015-11-04)

* Added support for Wikibase DataModel 4.x and 3.x
* Changed minimum Wikibase DataModel version to 2.5
* Added ci command that runs PHPUnit, PHPCS, PHPMD and covers tags validation
* Added TravisCI and ScrutinizerCI integration

### Version 0.6.2 (2014-12-24)

* Wikibase DataModel 1.x is no longer supported
* Added `ItemStore::getIdForEnWikiPage`

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
