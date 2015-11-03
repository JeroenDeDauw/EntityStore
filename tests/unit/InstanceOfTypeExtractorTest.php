<?php

namespace Tests\Queryr\EntityStore;

use DataValues\StringValue;
use Queryr\EntityStore\InstanceOfTypeExtractor;
use Wikibase\DataFixtures\Items\Berlin;
use Wikibase\DataModel\Entity\Item;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\DataModel\Snak\PropertyValueSnak;

/**
 * @covers Queryr\EntityStore\InstanceOfTypeExtractor
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class InstanceOfTypeExtractorTest extends \PHPUnit_Framework_TestCase {

	const INSTANCEOF_PROP_ID = 31;

	public function testGivenEmptyItem_nullIsReturned() {
		$this->assertNull( ( new InstanceOfTypeExtractor() )->getTypeOfItem( Item::newEmpty() ) );
	}

	public function testGivenBerlin_then515isReturned() {
		$this->assertSame(
			515,
			( new InstanceOfTypeExtractor() )->getTypeOfItem( ( new Berlin() )->newItem() )
		);
	}

	public function testGivenItemWithNoValueType_nullIsReturned() {
		$item = new Item();

		$item->getStatements()->addNewStatement( new PropertyNoValueSnak( self::INSTANCEOF_PROP_ID ) );

		$this->assertNull( ( new InstanceOfTypeExtractor() )->getTypeOfItem( $item ) );
	}

	public function testGivenItemWithNonIdType_nullIsReturned() {
		$item = new Item();

		$item->getStatements()->addNewStatement( new PropertyValueSnak(
			self::INSTANCEOF_PROP_ID,
			new StringValue( 'not an id' )
		) );

		$this->assertNull( ( new InstanceOfTypeExtractor() )->getTypeOfItem( $item ) );
	}

}