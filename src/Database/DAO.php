<?php

namespace \Concerto\Database;

/**
 * A DAO exception when there is an issue with a SQL
 * command.
 * 
 * @author wrey75@gmail.com
 *
 */
class DAOException extends Exception {
	
}


/**
 * Data Access Object. This object implements
 * the database access part of the application.
 * 
 * 
 * 
 */
class DAO {
	protected $conn; // Database connection (PDO)
	protected $log;  // Logger for errors.
	protected $canLogQueries;
	protected $converter;
	
	protected static $singleton = NULL;
	
	public function __construct(){
		$this->conn = NULL;
		$this->log = NULL;
		$this->converter = new BasicConverter();
		$this->canLogQueries = false;
	}
	

	/**
	 * 
	 * Connect to the database.
	 * 
	 * @param array $data the data to connect the database.
	 * @param string $login the login (if not provided in the $data array)
	 * @param string $password the password (if not provided in the $data array)
	 * @throws DAOException
	 */
	public function connect($data, $login = null, $password = null ){		
		$dsn = $data['dsn'];
		if( !$login ) $login = @$data['login'];
		if( !$password ) $password = @$data['password'];
		$options = @$data['options'];
		
		$log = @$data['log'];
		if( $log ){
			$this->log = new \Concerto\Logger( $log );
		}
		
		if( std::beginsWith($dsn, "mysql:") ){
			$this->converter = new MySQLConverter();
		}
		else {
			$this->log("Unknown database converter -- using basic converter instead");
			$this->converter = new BasicConverter();
		}
		
		if( @$data['canLogQueries'] ){
			$this->log->info("The SQL queries will be displayed.");
			$this->canLogQueries = true;
		}
		
		$this->conn = new PDO($dsn, $login, $password, $options);
		if( !$this->conn ){
			throw new DAOException( "Can not connect to database." );
		}
		
		// Begin a transaction in ALL cases.
		$this->beginTransaction();
	}


	/**
	 * Provides a default database connection. Usually, having only one
	 * database connection is sufficient for all the stuff. We are relying
	 * on this the '$SQL' global variable.
	 * 
	 * 
	 */
	static public function getDefault() {
		if( !self::$singleton ){
			// Create the connection
			$dsn = new DAO();
			$dsn->connect($GLOBALS['SQL']);
		}
		return self::$singleton;
	}
	
	
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
//
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
//		
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
//	
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
//	
// 	/**
// 	 * Insert the entity in the database.
// 	 * 
// 	 * @param $entity the entity to insert
// 	 *
// 	 */
// 	public function insert( DBEntity &$entity ) {
// 		$identityPropName = "";
// 		list( $sql, $identityPropName) = $this->get_insert_query(  $entity );
//	
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
//
// 	public function error( $message, $info ) {
//		
// 	}


///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////

	
	/**
	 * Begin a transaction. Note a transaction is automatically
	 * created when the connection is opened.
	 * 
	 * 
	 */
	public function beginTransaction(){
		$this->conn->beginTransaction();
		$this->fails = array();
	}
	
	
	/**
	 * Rollback the database connection. Every changes
	 * made into the database are discarded. Note the
	 * ROLLBACK creates a new transaction.
	 *
	 */
	public function rollback(){
		$this->conn->rollBack();
		$this->log->info( "ROLLBACK");
		$this->beginTransaction();
	}
	
	/**
	 *	Commits the changes into the database. Once done,
	 *	the changes become available to everybody. Note this
	 *	function will fail if some previous errors have been
	 *	detected during the transaction.
	 *
	 *	@return TRUE in case of success. The data is committed
	 *		and a new transaction is available. FALSE if an error
	 *		has been trapped during the transaction.
	 */
	public function commit(){
		if( count($this->fails) == 0 ){
			return $this->forceCommit();
		}
		else {
			$this->log->warn( "COMMIT failed due to previous errors." );
			return FALSE;
		}
	}
	
	/**
	 * Force a COMMIT even if errors have been raised.
	 * 
	 * @return true if the commit is successfull, false 
	 * 	if not.
	 *
	 */
	public function forceCommit(){
		$ok = $this->conn->commit();
		if( !$ok ){
			$this->error("Error during the COMMIT.");
		}
		else {
			$this->log->info( "COMMIT");
		}
		$this->beginTransaction();
		return $ok;
	}
	
	/**
	 * Secure the SQL output. This is necessary if
	 * some confidential information is stored in the
	 * database and the log files are output on 
	 * a media which is not as protected as the
	 * database itself.
	 * 
	 * Note that the use of SQL statements protects
	 * the data output.
	 * 
	 * @param string $sql the qury to protect.
	 */
	protected function secured($sql){
		if( $this->canLogQueries ){
			$secured = $sql;
		}
		else {
			// We get the first 20 characters for 
			// security reason.
			$secured = substr( $sql, 0, 20 );
			if( strlen($secured) > 20 ){
				$secured .= "...";
			}
		}
		return $secured;
	}
	
	
	/**
	 * Writes the SQL in the log file (only if the debug
	 * level is active. The time for retrieving or updating
	 * the database is displayed only if the query takes more
	 * than 10ms.
	 * 
	 * 
	 * When the query takes more than 1 second, a warning
	 * is displayed.
	 *
	 */
	protected function logQuery( $start, $sql, $nb ){
		if( $this->canLogQueries ){
			$stop = microtime(true);
			$duration = $stop - $start;
			if( $this->log->isDebugEnabled() || ($duration > 1.0) ){
				switch( $nb ){
					case -1 : $txt = ""; break;
					case  0 : $txt = "no data"; break;
					case  1 : $txt = "1 row"; break;
					default : $txt = "$nb rows"; break;
				}
				if( $duration < 0.010 ){
					// The time is not significant
					$chrono = "";
				}
				else {
					$chrono = sprintf( " in %1.2lf sec.", $duration );
				}
				$msg = "SQL: " . $this->secured($sql);
				if( !($chrono === "" && $txt === "") ){
					$msg .= "($txt$chrono)";
				}
				$this->log->debug( $msg );
			}
		}
	}
	
	/**
	 * Query an unique value like SELECT COUNT(*) or
	 * some other queries returning only one row containing
	 * only one value.
	 * 
	 * @param string $query the query
	 * @throws DAOException if an error occurred.
	 */
	public function queryValue( $query ){
		$rows = $this->query($query);
		if( $rows ){
			$nb = 0;
			foreach( $rows as $row ){
				$nb++;
				$value = $row[0];
			}
			if( $nb == 1 ) return $value;
			else throw new DAOException("More than one row returned.");
		}
		else {
			throw new DAOException("CAN NOT LOAD DATA (" . $this->secured($qury) . ")");
		}
	}
	
	/**
	 * Query to the database.
	 * 
	 * @param string $query the query (a SELECT)
	 * @param unknown $fetchmode the fetch mode (should not be given)
	 */
	public function query( $query, $fetchmode = PDO::FETCH_ASSOC ){
		$start = microtime(true);
		$results = $this->conn->query( $query );
		if( $results === FALSE ){
			$this->sqlError($query);
		}
		else {
			$this->logQuery($start, $query, -1 );
		}
		return $results;
	}

	
	/**
	 * Execute SQL request (UPDATE, INSERT or DELETE).
	 *
	 */
	public function execute( $sql ){
		$start = microtime(true);
		$nb = $this->conn->exec( $sql );
		if( $nb === FALSE ){
			$this->sqlError($sql);
		}
		else {
			$this->logQuery($start, $sql, $nb);
		}
		return $nb;
	}
	
	/**
	 * Get the last ID. Note this function
	 * works well for MySQL or MSSqlServer but it
	 * is not possible to request for PosgreSQL
	 * or Oracle (because they are using SEQUENCEs).
	 * 
	 * @param string $colname the column name.
	 */
	public function getLastId( $colname = NULL ){
		return $this->conn->lastInsertId( $colname );
	}
	
// 	/**
// 	 *  Returns a compatible expression with "IN"
// 	 *  containing multiple values.
// 	 *  
// 	 *  @param $values the values.
// 	 *  @return the SQL string.
// 	 */
// 	public function arrayOf( $values ){
// 		$val = "";
// 		foreach( $values as $value ){
// 			if( $val ) $val .= ", ";
// 			$val .= static::$callback( $value );
// 		}
// 		return "( $val )";
// 	}
	
	/**
	 * Add an error in the list.
	 *
	 * @param string $sql the SQL request.
	 */
	protected function sqlError( $query ){
		$info = $this->getErrorInfo();
		$this->fails[] = array(
				'request' => $query,
				'error' => $info,
		);
		$this->log->warn("SQL ERROR:\n" . $this->secured($query) . "\nERR-" . $info[0] . ": " . $info[2]);
	}
	
	/**
	 * Get the last database error.
	 *
	 * @return multitype:
	 */
	public function getErrorInfo(){
		return $this->conn->errorInfo();
	}
	
}
