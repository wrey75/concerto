<?php

/**
 * The API Engine is the main class to create an API.
 *
 * <IfModule mod_rewrite.c>
 *  RewriteEngine On
 *  RewriteCond %{REQUEST_FILENAME} !-f
 *  RewriteCond %{REQUEST_FILENAME} !-d
 *  RewriteRule api/v1/(.*)$ api/v1/api.php?request=$1 [QSA,NC,L]
 * </IfModule>
 * 
 */
class Engine {


	public $root_dir;
	public $method;
	public $error;

	public function __construct( $root_dir ) {
		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Methods: *");
		$this->root_dir = $root_dir;
		$this->error = 200; // No error.
	}

	public function run(){
	}

	public function output($data){
		header("Content-Type: application/json");
		json_encode($response);
	}

}

