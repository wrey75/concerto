<?php

namespace Concerto\Database;



use Concerto;
/**
 * Data Access Object. This object implements
 * the database access part of the application.
 * 
 * 
 * 
 */
class DAO extends SQL {
	protected $converter;
	protected $db;
	
	protected static $singleton = NULL;
	
	public function __construct($data, $login = null, $password = null){
		parent::__construct();
		$this->converter = new BasicConverter();
		$this->connect($data, $login, $password);
	}
	

	/**
	 * 
	 * Connect to the database.
	 * 
	 * @param array $data the data to connect the database.
	 * @param string $login the login (if not provided in the $data array)
	 * @param string $password the password (if not provided in the $data array)
	 * @throws DAOException a DAOException (or a SQLException).
	 */
	protected function connect($data, $login = null, $password = null ){
		try {
			$dsn = $data['dsn'];
			$this->db = parent::connect($data,$login,$password);
			if( Concerto\std::beginsWith($dsn, "mysql:") ){
				$this->converter = new MySQLConverter();
			}
			else {
				$this->log("Unknown database converter -- using basic converter instead");
				$this->converter = new BasicConverter();
			}
		}
		catch(SQLException $e){
			throw new DAOException( "Can not connect to database.", SQL::CONNECT_ERROR, $e);
		}
	}


	/**
	 * Provides a default database connection. Usually, having only one
	 * database connection is sufficient for all the stuff. We are relying
	 * on this the '$SQL' global variable.
	 * 
	 * @param array $sql an array containing information to connect the
	 * database. If not provided, the global variable SQL (uppercase) is
	 * used. 
	 * 
	 */
	static public function getDefault($sql = null) {
		if( !self::$singleton ){
			// Create the connection
			if( !$sql ){
				if( !isset($GLOBALS['SQL']) ){
					throw new DAOException( "You must define a 'SQL' global variable.");
				}
				$sql = $GLOBALS['SQL'];
			}
			self::$singleton = new DAO($sql);
		}
		return self::$singleton;
	}
		
}
