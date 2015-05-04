<?php 

namespace Concerto;

/**
 * A very basic application.
 * 
 * To create a PHP application running out-of the box, use the following code:
 * 
 * <pre>
 *   &lt;php
 *     include __DIR__ . '/vendor/autoload.php";
 *     $myApp = new \Concerto\BasicApplication();
 *     echo $myApp->header();
 *     echo $myApp->footer();
 * </pre>
 * 
 * @author wrey75@gmail.com
 *
 */
class BasicApplication {
	
	public function __construct(){
		// Initialisation stuff..
	}
	
	
	public function header( $title, $infos ){
		
	}
	
	public function footer( $title, $infos ){
	
	}
}