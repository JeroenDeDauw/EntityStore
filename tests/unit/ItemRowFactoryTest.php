<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Data\EntityPageInfo;
use Queryr\EntityStore\ItemRowFactory;
use Wikibase\DataFixtures\Items\Berlin;

/**
 * @covers Queryr\EntityStore\ItemRowFactory
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemRowFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testNewRowForBerlin() {
		$itemRow = $this->newFactory()->newFromItemAndPageInfo(
			( new Berlin() )->newItem(),
			$this->newPageInfo()
		);

		$this->assertSame( 'kittens', $itemRow->getPageTitle() );
		$this->assertSame( '0000', $itemRow->getRevisionTime() );
		$this->assertSame( 9001, $itemRow->getRevisionId() );

		$this->assertSame( 64, $itemRow->getNumericItemId() );
		$this->assertSame( 'Berlin', $itemRow->getEnglishLabel() );
		$this->assertSame( 'the serialization', $itemRow->getItemJson() );
		$this->assertSame( 42, $itemRow->getItemType() );
	}

	public function testNewRowForItemWithoutEnglishLabel() {
		$item = ( new Berlin() )->newItem();
		$item->getFingerprint()->removeLabel( 'en' );

		$itemRow = $this->newFactory()->newFromItemAndPageInfo(
			$item,
			$this->newPageInfo()
		);

		$this->assertNull( $itemRow->getEnglishLabel() );
	}

	public function testNewRowForItemWithoutType() {
		$factory = new ItemRowFactory(
			$this->newStubItemSerializer(),
			$this->newStubTypeExtractor( null )
		);

		$itemRow = $factory->newFromItemAndPageInfo(
			( new Berlin() )->newItem(),
			$this->newPageInfo()
		);

		$this->assertNull( $itemRow->getItemType() );
	}

	private function newFactory() {
		return new ItemRowFactory(
			$this->newStubItemSerializer(),
			$this->newStubTypeExtractor( 42 )
		);
	}

	private function newPageInfo() {
		return ( new EntityPageInfo() )
			->setPageTitle( 'kittens' )
			->setRevisionTime( '0000' )
			->setRevisionId( '9001' );
	}

	private function newStubItemSerializer() {
		$serializer = $this->getMock( 'Serializers\Serializer' );

		$serializer->expects( $this->any() )
			->method( 'serialize' )
			->will( $this->returnValue( 'the serialization' ) );

		return $serializer;
	}

	private function newStubTypeExtractor( $returnValue ) {
		$extractor = $this->getMock( 'Queryr\EntityStore\ItemTypeExtractor' );

		$extractor->expects( $this->any() )
			->method( 'getTypeOfItem' )
			->will( $this->returnValue( $returnValue ) );

		return $extractor;
	}

}
