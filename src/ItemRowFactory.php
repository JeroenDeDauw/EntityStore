<?php

namespace Queryr\EntityStore;

use InvalidArgumentException;
use Queryr\EntityStore\Data\EntityPageInfo;
use Queryr\EntityStore\Data\ItemRow;
use Serializers\Serializer;
use Wikibase\DataModel\Entity\Item;

/**
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class ItemRowFactory {

	private $itemSerializer;
	private $typeExtractor;

	public function __construct( Serializer $itemSerializer, ItemTypeExtractor $typeExtractor ) {
		$this->itemSerializer = $itemSerializer;
		$this->typeExtractor = $typeExtractor;
	}

	/**
	 * @param Item $item
	 * @param EntityPageInfo $pageInfo
	 *
	 * @return ItemRow
	 * @throws InvalidArgumentException
	 */
	public function newFromItemAndPageInfo( Item $item, EntityPageInfo $pageInfo ) {
		if ( $item->getId() === null ) {
			throw new InvalidArgumentException( 'The items id cannot be null' );
		}

		return ( new ItemRow() )
			->setPageTitle( $pageInfo->getPageTitle() )
			->setRevisionId( $pageInfo->getRevisionId() )
			->setRevisionTime( $pageInfo->getRevisionTime() )
			->setEnglishLabel( $this->getEnglishLabel( $item ) )
			->setItemType( $this->getItemType( $item ) )
			->setNumericItemId( $item->getId()->getNumericId() )
			->setItemJson( $this->getItemJson( $item ) );
	}

	private function getItemJson( Item $item ) {
		return $this->itemSerializer->serialize( $item );
	}

	private function getItemType( Item $item ) {
		return $this->typeExtractor->getTypeOfItem( $item );
	}

	private function getEnglishLabel( Item $item ) {
		return $item->getFingerprint()->hasLabel( 'en' ) ?
			$item->getFingerprint()->getLabel( 'en' )->getText()
			: null;
	}

}