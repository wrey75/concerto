<?php

// DEPRECATED -- Moved elsewhere

// include_once __DIR__.'/../sql.class.php';
// include_once __DIR__.'/../logger.class.php';
// include_once __DIR__.'/../std.class.php';

// namespace \Concerto;

// /**
//  * A DAO exception when there is an issue with a SQL
//  * command.
//  * 
//  * @author wrey75@gmail.com
//  *
//  */
// class DAOException extends Exception {
	
// }


// /**
//  * Data Access Object. This object implements
//  * the database access part of the application.
//  * 
//  * 
//  */
// class DataAccessObject {
	
// 	public $conn; // Database connection (PDO)
// 	public $log;  // Logger for errors.
// 	protected static $singleton = NULL;
	
// 	public function __construct(){
// 		$this->conn = NULL;
// 		$this->log = NULL;
// 	}
	
// 	public function connect($dsn, $login, $password, $options = array() ){
// 		if( $options['logger'] ){
// 			$this->log = new \Concerto\Logger( $options['logger']  );
// 			unset( $options['logger'] );
// 		}
// 		$this->conn = new \Concerto\SQL($dsn, $login, $password, $options);
// 		if( !$this->conn ){
// 			throw new DAOException( "Can not connect to database:" );
// 		}
// 	}
	
// 	/**
// 	 * Provides a default database connection. Usually, having only one
// 	 * database connection is sufficient for all the stuff. Thene relying
// 	 * on this default connection is perfectly correct.
// 	 * 
// 	 * Note the connection is created if not already exists. The global
// 	 * $CONFIG variable is used.
// 	 * 
// 	 */
// 	static public function getDefault() {
// 		if( !self::$singleton ){
// 			// Create the connection
// 			$dsn = new DataAccessObject();
// 			$dsn->connect(DAO::$dsn, DAO::$login, DAO::$password, DAO::$options);
// 		}
// 		return self::$singleton;
// 	}
	
	
// 	/**
// 	 * Computes the SQL INSERT statement.
// 	 * 
// 	 * @param DBEntity $entity the entity
// 	 * @return array the SQL statemement.
// 	 */
// 	protected function get_insert_query( DBEntity $entity ){
// 		$cols = $entity->getColumns();
// 		$var = 1;
// 		$vars = array();
		
// 		// Create the INSERT clauses
// 		$collist = "";
// 		$values = "";
// 		$first = true;
// 		$identityPropName = $identityColumnName = null;
// 		foreach( $cols as $col => $definition ){
// 			$included = FALSE;
// 			if( isset($entity->$col) ){
// 				if( $definition->isVersion() ){
// 					// We override the value (that should be
// 					// zero by the way).
// 					$entity->$col = 1;
// 				}
		
// 				// Only defined properties are included in the
// 				// INSERT statement
// 				$val = $entity->$col;
// 				if( isset($val) ){ // Ignore NULL values
// 					if( $first ){
// 						$first = false;
// 					}
// 					else {
// 						$collist .= ", ";
// 						$values .= ", ";
// 					}
// 					$collist .= $definition->getName();
// 					$values .= $definition->sqlOf($val);
// 					$included = TRUE;
// 				}
// 			}
// 			if( !$included && $definition->isSequence() ){
// 				$identityPropName = $col;
// 				$identityColumnName = $definition->getName();
// 			}
// 		}
// 		$table = $this->getTableName();
// 		$sql = "INSERT INTO $table ( $collist ) VALUES ( $values );";
// 		return [ $sql, $identityPropName ];
// 	}
	
// 	/**
// 	 * Create the SQL statement for inserting the row.
// 	 * 
// 	 * @param $entity the entity to insert.
// 	 * @return the SQL code to execute.
// 	 * 
// 	 */
// 	public function getInsertQuery( DBEntity $entity ){
// 		$data = get_insert_query($entity);
// 		return $data[0];
// 	} 
	
// 	/**
// 	 * Insert the entity in the database.
// 	 * 
// 	 * @param $entity the entity to insert
// 	 *
// 	 */
// 	public function insert( DBEntity &$entity ) {
// 		$identityPropName = "";
// 		list( $sql, $identityPropName) = $this->get_insert_query(  $entity );
	
// 		$nb_rows = $this->conn->execute($sql);
// 		if( $nb_rows < 1 ){
// 			$this->error(_("INSERT FAILED"), [ "sql"=>$sql, "entity"=>$entity ]);
// 			return FALSE;
// 		}
// 		else if( $identityPropName ){
// 			// Get the last inserted ID if apply...
// 			$entity->$identityPropName = $this->conn->getLastId( $identityColumnName );
// 		}
// 		return TRUE;
// 	}
	
	
// 	public function error( $message, $info ) {
		
// 	}
// }