<?php


/**
 * This class is intended to be a thin layer to
 * the database. Basically, this class uses PDO
 * to connect the database and works with
 * transactions. Stores the list of the errors
 * found in the transaction. Also can log the queries.
 *
 * @author wrey75
 *
 */
class SQL {

	/**
	 * Declares a connection.
	 *
	 * @param unknown $dsn
	 * @param unknown $login
	 * @param unknown $password
	 * @param unknown $options
	 */
	public function __construct( $dsn, $login, $password, $options = array() ){
		$this->log = KLogger::getNull();
	
		$this->db = new PDO( $dsn, $login, $password, $options );
		$this->log->debug("Connected to $dsn" );
		$this->beginTransaction();
	}
	
	/**
	 * Physical PDO connection.
	 * 
	 */ 
	private $db;
	private $fails = array(); // Number of SQL queries which failed and the data linked.
	public $log;

	/**
	 * Add an error in the list.
	 * 
	 * @param string $sql the SQL request.
	 */
	protected function error( string $sql ){
		$info = $this->getErrorInfo();
		$this->fails[] = array(
			'request' => $sql,
			'error' => $info,
		);
		$this->log->warn("SQL ERROR:\n$sql\nERR-" . $info[0] . ": " . $info[2]);
	}

	/**
	 * Get the last database error.
	 * 
	 * @return multitype:
	 */
    public function getErrorInfo(){
        return $this->db->errorInfo();
    }

    public function beginTransaction(){
    	$this->db->beginTransaction();
    	$this->fails = array();
    }
    
    
	/**
	 * Roolback the database connection. Every changes
	 *	made into the database are discarded.
	 *
	 */
	public function rollback(){
		$this->db->rollBack();
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
	 *		and a new transaction is available.  
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
	 */
	public function forceCommit(){
		$this->log->info( "COMMIT");
		$this->db->commit();
		$this->beginTransaction();
		return TRUE;
	}

// 	/**
// 	 * Get a database connection.
// 	 */
// 	public static function getConnection(){
// 		if( !isset(self::$instance) ){
// 			self::$instance = new SQL( self::$dsn, self::$login, self::$password, self::$options );
// 		}
// 		return self::$instance;
// 	}

	/**
	 * Set the logger for SQL requests.
	 * 
	 * @param Logger $logger
	 */
	public function setLogger( Logger $logger ){
		$this->log = $logger;
	}
	
	


    /**
     * Provides an equivalent to htmlentities()
     * but for SQL data retrieved through the
     * database.
     */
    public static function html( $str ){
        $html = htmlspecialchars( $str );
        return $html;
    }

// 	/**
// 	 * Makes the string compatible with SQL to
// 	 * avoid both SQL injections and SQL syntax
// 	 * errors.
// 	 *
// 	 * @param $str the string to put as SQL compatible.
// 	 * 	Note that zero-length strings and NULL values
// 	 * 	are translated as "NULL".
// 	 */
// 	public static function sqlstr( $str ){
// 		if( strlen($str) == 0 ) {
// 			// Empty string generates a NULL string
// 			return "NULL";
// 		}
// 		$ret = str_replace( "'", "''", $str );
// 		return "'" . $ret . "'";
// 	}

// 	public static function sqlint( $val ){
// 		if( !$val && $val !== 0 ){
// 			// Empty string generates a NULL string
// 			return "NULL";
// 		}
// 		$ret = "" . $val;
// 		return $ret;
// 	}

// 	private static function sqlformattime( $dt, $format ){
// 		if( $dt === NULL ) return "NULL";
// 		if( $dt instanceof DateTime ){
// 			$formatted = $dt->format( $format );
// 		}
// 		else {
// 			$formatted = date( $format, $dt );
// 		}
// 		return static::sqlstr($formatted);
// 	}

// 	/**
// 	 * Return a date in a string format compatible
// 	 * with SQL format
// 	 *
// 	 * @param $dt the timestamp (if null, the current
// 	 * 	time is used).
// 	 */
// 	public static function sqltime( $dt = null ) {
// 		return static::sqlformattime( $dt, "Y-m-d H:i:s");
// 	}

// 	public static function sqldate( $dt = null ){
// 		return static::sqlformattime( $dt, "Y-m-d");
// 	}

// 	/**
// 	 * Return the current date in a SQL compatible way.
// 	 *
// 	 */
// 	public static function now(){
// 		return date('Y-m-d H:i:s');
// 	}

	/**
	 * Writes the SQL in the log file (only if the debug
	 * level is active. The time for retrieving or updating
	 * the database is displayed only if the query takes more
	 * than 10ms.
	 * When the query takes more than 1 second, a warning
	 * is displayed.
	 *
	 */
	protected function logquery( $start, $sql, $nb ){
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
			$msg = "SQL: $sql";
			if( !($chrono === "" && $txt === "") ){
				$msg .= "($txt$chrono)";
			}
			$this->log->debug( $msg );
		}
	}

	public function queryValue( $sql ){
		$rows = $this->query($sql);
		if( $rows ){
			$nb = 0;
			foreach( $rows as $row ){
				$nb++;
				$value = $row[0];
			}
			if( $nb == 1 ) return $value;
			else throw new Exception("More than one row returned.");
		}
		else {
			throw new Exception("CAN NOT LOAD DATA ($sql)");
		}
	}
	
	public function query( $sql, $fetchmode = PDO::FETCH_ASSOC ){
		$start = microtime(true);
		$results = $this->db->query( $sql );
		if( $results === FALSE ){
			$this->error($sql);
		}
		else {
			$this->logquery($start, $sql, -1 );
		}
		return $results;
	}

	/**
	 * Execute SQL request (UPDATE, INSERT or DELETE).
	 *
	 */
	public function execute( $sql ){
		$start = microtime(true);
		$nb = $this->db->exec( $sql );
		if( $nb === FALSE ){
			$this->error($sql);
		}
		else {
			$this->logquery($start, $sql, $nb);
		}
		return $nb;
	}

	public function getLastId( $colname = NULL ){
		return $this->db->lastInsertId( $colname );
	}

	/**
	 *  Returns a compatible expression with "IN"
	 *  containing multiple values.
	 */
	public function arrayof( $values, $callback = "sqlstr" ){
		$val = "";
		foreach( $values as $value ){
			if( $val ) $val .= ", ";
			$val .= static::$callback( $value );
		}
		return "( $val )";
	}
}

