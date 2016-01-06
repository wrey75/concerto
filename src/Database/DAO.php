<?php

namespace Concerto\Database;



use Concerto;
/**
 * Data Access Object. This object implements
 * the database access part of the application.
 * 
 * This part is in charge of the entities (the SQL
 * underlying is reponsible of the access). In addition
 * 
 */
class DAO extends SQL {
	protected $converter;
	protected $db;
	
	protected static $singleton = NULL;
	
	/**
	 * Creates a DAO.
	 * 
	 * @param array|string $data the connection information (expressed as an array) or
	 * the DSN if only a string is provided.
	 * @param string $login the login (if not NULL, replace the login give in $data)
	 * @param string $password the password (if not NULL, replace the password give in $data)
	 */
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
		if( !is_array($data) ){
			// Apply as an array 
			$data = ['dsn'=>$data];
		}
		
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
	
	/**
	 * Runs a native SQL query.
	 * 
	 * @param string $sql the SQL query.
	 * @return array the rows or NULL in case of error.
	 */
	public function dbquery( $sql ){
		$rows = $this->query($sql);
		return $rows;
	}
	
	/**
	 * Insert the entity in the database.
	 * 
	 * @param DBEntity $obj the entity to insert.
	 * 
	 * @return TRUE if the record has been successfully
	 * 		inserted.
	 *
	 */
	public function insert( &$obj ) {
		// Signgle record.
		$columns = $obj->getColumns();
	
		// Create the INSERT clauses
		$columnList = [];
		$values = [];
		$identityPropName = $identityColumnName = null;
		foreach( $columns as $col => $definition ){
			$included = FALSE;
			if( isset($obj->$col) ){
				if( $definition->isVersion() ){
					// We do not check the original value expected to
					// be zero but not necessary if the record is created
					// from another one.
					// Nverthesless, we force a value of 1 (version 1 of the record).
					$obj->$col = 1;
				}
				
				// Only defined properties are included in the
				// INSERT statement
				$val = $obj->$col;
				if( isset($val) ){ // Ignore NULL values
					$columnList[] = $definition->getName();
					$values[] = $this->converter->sqlOf($definition, $val);
					$included = TRUE;
				}
			}
			
			if( !$included && $definition->isSequence() ){
				$identityPropName = $col;
				$identityColumnName = $definition->getName();
			}
		}
		$table = $obj->getTableName();
		$sql = "INSERT INTO $table ( " 
				. implode(",", $columnList) . " ) VALUES ( "
				. implode(",", $values) . ' );';
	
		$rows = $this->execute($sql);
		if( $rows < 1 ){
			$this->sqlError($sql, "Nothing inserted.");
			return FALSE;
		}
		else if( $identityPropName ){
			// Get the last inserted ID if apply...
			$obj->$identityPropName = $this->getLastId( $identityColumnName );
		}
		$obj->setDAO($this);
		return TRUE;
	}
	
	
	/**
	 * Update the entity in the database. The entity
	 * knows how to update it based on the primary key.
	 *
	 * @param DBEntity $obj the object to update.
	 */
	public function update(&$obj) {
		$versionColumn = null;
		$columns = $obj->getColumns();
		
		if( !$obj->isPersistent() ){
			trigger_error("Object $obj is not set as persistent!", E_USER_NOTICE);
			$obj->setDAO($this);
		}
	
		// Create the UPDATE clause
		$set = [];
		$where = [];
		$data = get_object_vars($obj);
		foreach( $columns as $name => $definition ){
			$val = @$data[$name];
			$columnName = $definition->getName();
			$sqlVal = $this->converter->sqlOf($definition, $val);
			if( $definition->isPrimaryKey() ){
				//if (strlen($where) > 0) $where .= " AND ";
				$where[] = "$columnName = $sqlVal";
			}
			else if( $definition->isVersion() ){
				// if (strlen($where) > 0) $where .= " AND ";
				$where[] = "$columnName = $sqlVal";
				
				// if( strlen($set) > 0 ) $set .= ", ";
				$set[] = "{$columnName} = {$columnName} + 1";
				$versionColumn = $name;
			}
			else if( !$definition->isAutomatic() && !is_null($sqlVal) ) {
				//if( strlen($set) > 0 ) $set .= ", ";
				$set[] = "$columnName = $sqlVal";
			}
		}
		$table = $obj->getTableName();
		$sql = "UPDATE $table SET " . implode( ", ", $set) . " WHERE " . implode( " AND ", $where) ;
		
		
		$nb = $this->execute($sql);
		if( $nb > 1 ){
			// $log = KLogger::getDefault();
			$errs = $dao->errorInfo();
			$message .= "\nSQL: $sql\nError code: $errs[0]\nError message: $errs[2]";
			$this->dao->sqlError( $sql, $errs[2]);
			if( (error_reporting() && E_ERROR ) == E_ERROR ){
				echo "<pre>$message</pre>";
			}
			die(1);
	
		}
		else if( $nb == 1 ){
			if( $versionColumn ){
				// Update the version (done during the UPDATE)
				// reflect on the current.
				$this->$versionColumn++;
			}
		}

		return ($nb < 2); // If an update DOES NOT UPDATE anything (no changes in columns, it can return 0).
	}
	
	/**
	 * Calculates the the WHERE part based on the
	 * primary key. Used internally for deletion and reload
	 * of an object.
	 * 
	 * @param DBEntity $obj the current object.
	 * 
	 */
	protected function getPrimaryWhere($obj) {
		$columns = $obj->getPrimaryColumns();
		$where = [];
		foreach( $columns as $name => $definition ){
			$val = $obj->$name;
			$where[] = $definition->getName() . " = " . $this->converter->sqlOf($definition, $val);
		}
		return implode( " AND ", $where);
	}
	
	/**
	 * Delete the current instance.
	 *
	 * @param DBEntity $obj the current object.
	 * 
	 */
	public function delete($obj){
		$whereClause = $this->getPrimaryWhere($obj);
		$sql = "DELETE FROM " . $obj->getTableName() . " WHERE {$whereClause};";
		$nb = $this->execute($sql);
		if( $nb != 1 ){
			$this->sqlError($sql, "Delete of $nb rows instead of one!" );
		}
		$obj->setDAO(NULL);
		return TRUE;
	}
	
	
	/**
	 * Delete rows in the entity table based on the
	 * where clause given as parameter. The entity passed
	 * as parameter is only to access the database table name.
	 *
	 * @param DBEntity $sample a sample of the entity.
	 * @param string|array $where a WHERE expression.
	 * @return the number deleted records.
	 * 
	 */
	public function deleteRows( $sample, $where ){
		$whereClause = $this->getWhereClause( $sample, $where );
		$table = $sample->getTableName();
		$sql = "DELETE FROM $table $whereClause;";
		$nb = $this->execute($sql);
		return $nb;
	}
	
	/**
	 * Get the SQL where clause. Includes the "WHERE"
	 * keyword.
	 *
	 *
	 *
	 * @param string|array $where the where clause
	 * if SQL or an array
	 * @return the SQL WHERE (beginning with "WHERE" keyword).
	 */
	public function getWhereClause( $obj, $where = null ){
		$clause = "";
		if( $where ){
			if( is_array($where) ) {
				$definitions = $obj->getColumns();
				// Make a suitable WHERE clause
				foreach ( $where as $prop => $val ){
					$def = $definitions[$prop];
					$clause .= (strlen($clause) > 0 ? " AND " : "WHERE ");
					if( $val === NULL ){
						// Special case
						$clause .= $def->getName() . ' IS NULL';
					}
					else {
						$clause .= $def->getName() . ' = ' . $this->converter->sqlOf($def, $val);
					}
				}
			}
			else {
				// Use as it is...
				$clause = "WHERE $where";
			}
		}
		return $clause;
	}
	
	/**
	 * Load entities based on a where clause.
	 *
	 * @param DBEntity $obj a entity (used for the correct format).
	 * @param string|array $where the restrictions to apply
	 * @param array $order the ordering if apply.
	 *
	 * @return array an array of DBEntity objects.
	 */
	public function select( $obj, $where = null, $order = null ){
		$whereClause = $this->getWhereClause($obj, $where);
		$orderClause = $obj->getOrderClause( $order );
	
		// Now select data
		$sql = "SELECT * FROM " . $obj->getTableName()
				. " " . $whereClause
				. " " . $orderClause;
		$results = $this->query($sql);
		return $this->sql2entities($obj, $results);
	}
	
	public function selectWithCallback( $obj, $callback, $where = null, $order = null ){
		$whereClause = $this->getWhereClause($obj, $where);
		$orderClause = $obj->getOrderClause( $order );
	
		// Now select data
		$sql = "SELECT * FROM " . $obj->getTableName()
		. " " . $whereClause
		. " " . $orderClause;
		$results = $this->query($sql);
		if( $results ){
			foreach( $results as $row ){
				call_user_function( $callback, $row);
			}
		}
	}
	
	
	/**
	 * Same as the select() function but returns only the first
	 * line or NULL if nothing found. Throws an exception if several
	 * instances are returned.
	 *
	 * @param DBEntity $obj a entity (used for the correct format).
	 * @param string a WHERE condition.
	 * @return null|DBEntity the entity or NULL if not found.
	 *
	 */
	public function selectUnique( $obj, $where ){
		$data = $this->select($obj, $where);
		$nb = count($data);
		if( $nb == 0 ){
			// No entity found.
			return null;
		}
		else if( $nb == 1 ){
			return $data[0];
		}
		throw new DAOException("Too many lines returned.");
	}
	
	/**
	 * Count the number of records based on a WHERE clause.
	 * 
	 * @param DBEntity $obj a entity (used for the correct format).
	 * @param string a WHERE condition.
	 * @return int the number of records found.
	 */
	public function count( $obj, $where ){
		$whereClause = $this->getWhereClause($obj, $where);
		$sql = "SELECT COUNT(*) FROM " . $obj->getTableName()
		. " " . $whereClause;
		$results = $this->query($sql);
		
		$nb = 0;
		foreach( $results as $row ){
			$nb = $row[0];
		}
		return $nb;
	}
	
	/**
	 * Reload from the database.
	 *
	 * @param DBEntity $obj a entity (used for the correct format).
	 * @return NULL|DBEntity the entity of NULL if
	 * 		deleted.
	 */
	public function reload(&$obj) {
		$whereClause = $this->getPrimaryWhere($obj);
		$obj = $this->selectUnique($where);
		return $obj;
	}
	
	
	/**
	 * Convert a SQL record to an entity.
	 * 
	 * NOTE: this method uses the $obj to store data in
	 * it.
	 *
	 * @param DBEntity $obj an entity that will be modified.
	 * @param array $row an associative array containing the column
	 * names as keys and their respective values.
	 *
	 */
	public function sql2entity( &$obj, $row ){
		$definitions = $obj->getColumns();
		foreach( $definitions as $prop => $def ){
			$val = $row[$def->getName()];
			if( isset( $val) ){
				$obj->$prop = $this->converter->fromSql($def, $val);
				// echo "CLASS=" . get_class($obj) . "; V= $val; P=$prop => SQL= {$this->$prop}\n";
			}
			else {
				// Set to NULL to avoid issues on non definied properties.
				$obj->$prop = null;
			}
		}
		return $obj;
	}
	
	
	/**
	 * Convert the results of a SQL query to
	 * an array of entities.
	 * 
	 * @param DBEntity $fakeObj the entity.
	 * @param array $rows the rows returned by the query.
	 */
	public function sql2entities( $fakeObj, $rows ){
		$entities = [];
		if( $rows ){
			foreach( $rows as $row){
				$obj = $fakeObj->newInstance();
				$obj->setDAO($this);
				$entities[] = $this->sql2entity($obj, $row);
			}
		}
		return $entities;
	}
	

	/**
	 * The scanner for fetching each row.
	 * 
	 * @param unknown $rows
	 * @param unknown $userCallback the user function callback.
	 */
	protected function fetch_scanner( $rows, $userCallback  ){
		if( $rows ){
			foreach( $rows as $row){
				call_user_function( $userCallback, $row);
			}
		}
	}
	
	/**
	 * SELECT in the database based on a plain SQL request.
	 *
	 * @param string $query the SQL request.
	 * @return DBEntity[] an array containing the entities.
	 */
	public function selectBySQL( $obj, $sql ){
		$rows = $this->query($sql);
		$results = $this->sql2entities($obj, $rows);
// 		// Return the results in an array
// 		$definitions = $obj->getColumns();
// 		$results = [];
// 		if( $rows ){
// 			// In case of no data, the result set is simply NULL
// 			foreach( $rows as $data ){
// 				$results[] = $this->sql2entity( $definitions, $data );
// 			}
// 		}
 		return $results;
	}
	
	/**
	 *	This function is defined to retrieve
	 *	entities having an unique (technical) identifier
	 *	name "id".
	 *
	 * @param DBEntity $obj a entity (used for the correct format).
	 *
	 */
	function getById( $obj, $id ){
		$keys = $obj->getPrimaryColumns();
		$where = array();
		foreach( $keys as $key => $col ){
			$where[$key] = $id;
		}
		$unique = $this->selectUnique( $obj, $where );
		return $unique;
	}
}
