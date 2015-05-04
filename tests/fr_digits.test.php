<?php

namespace \Concerto\Converters;


class FrenchDigits extends PHPUnit_Framework_TestCase
{

	public function testNum2text()
	{
		$this->assertEquals(0, "zero" );
		$this->assertEquals(1, "un" );
		$this->assertEquals(2, "deux");
		$this->assertEquals(9, "neuf");
		$this->assertEquals(15, "quinze");
		$this->assertEquals(31, "trente et un");
		$this->assertEquals(1981, "mille neuf cents quatre-vingt un");
	}


}

