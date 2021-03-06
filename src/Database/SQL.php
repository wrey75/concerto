<?php

namespace Concerto\Database;

use PDO;
use Concerto\Logger;

/**
 * SQL Connection. This object implements
 * the database access part of the application.
 * 
 * 
 * 
 */
class SQL {
	static $showLogQueryWarning = FALSE;
	protected $conn; // Database connection (PDO)
	
	/** @var Logger */
	protected $log;  // Logger for errors.
	protected $canLogQueries;
	
	const CONNECT_ERROR = 100;
		
	public function __construct(){
		$this->conn = NULL;
		$this->log = Logger::getDefault();
		$this->canLogQueries = false;
		
// 		if( $data !== NULL ){
// 			try {
// 				$this->connect($data, $login, $password );
// 			}
// 			catch( \PDOException $e ){
// 				$msg = 'pp';
// 				if( $e->getCode() == 2002 ){
// 					$msg = "Check your PHP.INI file: " . php_ini_loaded_file();
// 				}
// 				$msg = "Can not connet to the RDBMS. $msg";
// 				throw new SQLException($msg, SQL::CONNECT_ERROR, $e);
// 			}
// 		}
	}
	

	/**
	 * 
	 * Connect to the database.
	 * 
	 * @param array $data the data to connect the database. Can be a string (in this case, it is the DSN)
	 * @param string $login the login (if not provided in the $data array)
	 * @param string $password the password (if not provided in the $data array)
	 * @throws SQLException
	 */
	protected function connect($data, $login = null, $password = null){
		if( !is_array($data) ){
			// Apply as an array
			$data = ['dsn'=>$data];
		}
		
		$dsn = $data['dsn'];
		if( !$login ) $login = @$data['login'];
		if( !$password ) $password = @$data['password'];
		$options = [];
		if( @is_array($data['options']) ){
			$options = $data['options'];
		}
		
		$log = @$data['log'];
		if( $log ){
			$this->log = new \Concerto\Logger( $log );
		}
		
		if( @$data['canLogQueries'] ){
			if( !SQL::$showLogQueryWarning ){
				SQL::$showLogQueryWarning = true;
				$this->log->debug("The SQL queries will be displayed.");
				$this->canLogQueries = true;
			}
		}
		
// 		echo "** $dsn **\n";
// 		echo "** $login **\n";
// 		echo "** $password **\n";
// 		echo "** $options **\n";
		try {
			$this->conn = new \PDO($dsn, $login, $password, $options);
			if( !$this->conn ){
				throw new SQLException( "Can not connect to database." );
			}
		}
		catch( \PDOException $e ){
			throw new SQLException( "Can not connect to database.", SQL::CONNECT_ERROR, $e );
		}
		
		// Begin a transaction in ALL cases.
		$this->beginTransaction();
	}

	
	/**
	 * Begin a transaction. Note a transaction is automatically
	 * created when the connection is opened.
	 * 
	 * 
	 */
	public function beginTransaction(){
		// $this->log->info( "BEGIN TRANSACTION");
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
			if( strlen($sql) > 20 ){
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
	 * @throws SQLException if an error occurred.
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
			else throw new SQLException("More than one row returned.");
		}
		else {
			throw new SQLException("CAN NOT LOAD DATA (" . $this->secured($qury) . ")");
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
	 * @param string $info an information complement.
	 * 
	 */
	protected function sqlError( $query, $info = null ){
		$info = $this->conn->errorInfo();
		$this->fails[] = array(
				'request' => $query,
				'error' => $info,
		);
		if( $info ){
			$this->fails['info'] = $info;
		}
		$this->log->warn("SQL ERROR:\n" . $this->secured($query) . "\nERR-" . $info[0] . ": " . $info[2]);
	}
	
	/**
	 * Get the last database error. The associative array returned
	 * contains the following information:
	 * 
	 * <ul>
	 *   <li><code>state</code>: SQLSTATE error code (a five characters
	 *   alphanumeric identifier defined in the ANSI SQL standard).</li>
	 *   <li><code>code</code>: Driver specific error code.</li>
	 *   <li><code>message</code>: Driver specific error message.</li>
	 * </ul>
	 *
	 * @return array an associative array (see description)
	 * 	or NULL if no error.
	 */
	public function getLastError(){
		$infos = $this->conn->errorInfo();
		if( !$infos ) return NULL;
		return [
			'state' => $infos[0],
			'code' => $infos[1],
			'message' => $infos[2]
		];
	}
}
