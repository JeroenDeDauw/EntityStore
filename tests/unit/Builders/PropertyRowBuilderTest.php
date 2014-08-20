<?php

namespace Tests\Queryr\EntityStore;

use Queryr\EntityStore\Builders\PropertyRowBuilder;

/**
 * @covers Queryr\EntityStore\Builders\PropertyRowBuilder
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class PropertyRowBuilderTest extends \PHPUnit_Framework_TestCase {

	public function testCanConstruct() {
		new PropertyRowBuilder();
		$this->assertTrue( true );
	}

}
