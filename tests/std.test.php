<?php

namespace Concerto;


/**
 * This is a basic class for PHP development. It is
 * intended to implement very useful stuff not available
 * directly in the PHP language.
 * 
 * The public function are "static".
 * 
 * @author wrey75@gmail.com
 *
 */
class stdTest extends PHPUnit_Framework_TestCase {

	public function testLastChar()
	{
		$this->assertEquals( std::lastChar(""), '\0'); 
		$this->assertEquals( std::lastChar("Helene"), 'e'); 
		$this->assertEquals( std::lastChar("Leo"), "o"); 
	}
	
	
	public function testScript()
	{
		$this->assertEquals( std::script("/hello.js"), '<script src="/hello.js" type="text/javascript">' );
	}

	public function testTag()
	{
		$this->assertEquals( std::tag("ul"), "<ul>" );
		$this->assertEquals( std::tag("LI"), "<LI>" );
		$this->assertEquals( std::tag("/ul"), "</ul>" );
		$this->assertEquals( std::tag("br/"), "<br />" );
		$this->assertEquals( std::tag("img", [ "src"=> "hello.png"]), '<img src="hello.png">' );
	}

	public function testTagln(){
		$this->assertEquals( std::tagln("p"), "<p>\n" );
	}


	public function testCheckLuhn(){
		$array = [		
			"4111111111111111" => true
			];

		foreach( $array as $k=>$v ){
			if( $v ){
				$this->assertTrue( std::checkLuhn($k) );
			}
			else {
				$this->assertFalse( std::checkLuhn($k) );
			}
		}
	}



   
}


