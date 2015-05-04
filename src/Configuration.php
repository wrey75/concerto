<?php

// namespace Convcerto;

// class Configuration {
// 	protected $config;
// 	protected $instance;
	
// 	public static function getDefault( $vars = "{}" ){
// 		if( !$this->instance ){
// 			$this->instance = new Configuration( $vars );
// 		}
// 		return $this->instance;
// 	}
	
// 	/**
// 	 * Create a configuration.
// 	 * 
// 	 * @param string|array $vars the configuration. Can be a JSON string
// 	 * 	or a PHP array, 
// 	 */
// 	public function __construct( $vars = "{}" ){
// 		if( is_string($vars) ){
// 			// Convert to an associative array
// 			$vars = json_decode($vars, TRUE);
// 		}
// 		$this->config = $vars;
// 	}
	
// 	/**
// 	 * Set the configuration value.
// 	 * 
// 	 * @param string $key the configuration key.
// 	 * @param unknown $value the value to set.
// 	 */
// 	public function set( string $key, $value ){
		
// 		$loc = &$this->config;
// 		foreach(explode('.', $key) as $step)
// 		{
// 			$loc0 = &$loc;
// 			if( isset($loc[$step]) && is_array($loc[$step]) ){
// 				$loc = &$loc[$step];
// 			}
// 			else {
// 				$loc0[$step] = array();
// 				$loc = &$loc0[$step];
// 			}
// 		}
// 		$loc = $value;
// 	}
	
	
// }
