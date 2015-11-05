<?php

namespace Concerto\Database;

/**
 * Describes an entity. An entity is a simple
 * description containing properties. 
 * 
 * @author wre
 *
 */
abstract class DBEntity {

	const COLNAME = 0;
	const SQLTYPE = 1;
	
	/** DAO */
	private $dao = null;

	/**
	 * Must return the table name of this
	 * entity
	 */
	public static function getTableName() {
		throw new Exception("DBEntity::getTableName() must be implemented by you entity class.");
	}
	
	/**
	 * Construct the entity.
	 * 
	 * @param DAO $dao the DAO to use, If not given, the default
	 * 		DAO is used.
	 */
	public function __construct($dao = NULL) {
		if( !$dao ) $dao = DAO::getDefault();
		$this->dao = $dao;
	}

	/**
	 * Get the columns for this object. We consider
	 * the properties set and not starting with "_"
	 * (this will permit to the programmer to add
	 * properties with such names without persistence
	 * in the database).
	 *
	 *  The entities should overwrite this function
	 *  with their own array of properties depending
	 *  of the columns in the database.
	 *
	 *  Note the returned value is an array containing
	 *  the column name as key and its current value. It
	 *  is a good way to get the properties of the object
	 *  expressed as an array.
	 *
	 */
	static public function getColumns() {
		throw new Exception("DBEntity::getColumns() must be implemented by you entity class.");
	}

	/**
	 * Runs a native SQL query.
	 * 
	 * @param string $sql the SQL query.
	 * @return array the rows or NULL in case of error.
	 */
	public function dbquery( $sql ){
		// $dao = DAO::getDefault();
		$rows = $this->dao->query($sql);
		return $rows;
	}

	/**
	 * This method must return the columns
	 * of the primary key in an array. This
	 * function works exactly as getColumns()
	 * but filters only the columns part of
	 * the primary key.
	 *
	 * If there is no primrary key defined, this
	 * will return a null pointer instead of a
	 * empty array because an entity MUST have a
	 * primary key.
	 *
	 */
	public static function getPrimary(){
		$primary = null;
		$cols = static::getColumns();
		foreach( $cols as $prop => $definition ){
			if( $definition->isPrimaryKey() ){
				$primary[$prop] = $definition;
			}
		}
		return $primary;
	}

	/**
	 * Insert the entity in the database.
	 *
	 */
	public function insert( ) {
		$cols = $this->getColumns();
		$var = 1;
		$vars = array();

		// Create the INSERT clauses
		$collist = "";
		$values = "";
		$first = true;
		$identityPropName = $identityColumnName = null;
		foreach( $cols as $col => $definition ){
			$included = FALSE;
			if( isset($this->$col) ){
				if( $definition->isVersion() ){
					// We override the value (that should be
					// zero by the way).
					$this->$col = 1;
				}
				// Only defined properties are included in the
				// INSERT statement
				$val = $this->$col;
				if( isset($val) ){ // Ignore NULL values
					if( $first ){
						$first = false;
					}
					else {
						$collist .= ", ";
						$values .= ", ";
					}
					$collist .= $definition->getName();
					$values .= $definition->sqlOf($val);
					$included = TRUE;
				}
			}
			if( !$included && $definition->isSequence() ){
				$identityPropName = $col;
				$identityColumnName = $definition->getName();
			}
		}
		$table = $this->getTableName();
		$sql = "INSERT INTO $table ( $collist ) VALUES ( $values );";

		$rows = $this->dao->execute($sql);
		if( $rows < 1 ){
			$this->dao->sqlError($sql, "Nothing inserted.");
			return FALSE;
		}
		else if( $identityPropName ){
			// Get the last inserted ID if apply...
			$this->$identityPropName = $db->getLastId( $identityColumnName );
		}
		return TRUE;
	}

	
	/**
	 * Return the value stored in the entity
	 * based on the column name. Used to easily
	 * access values dynamically.
	 */
	protected function get($name) {
		$arr = get_object_vars($this);
		return $arr[ $name ];
	}

	/**
	 * Update the entity in the database.
	 *
	 * @param DAO $dao the DAO connection (not mandatory).
	 */
	public function update() {
		$ret = true;
		
		$versionColumn = null;
		$cols = $this->getColumns();
		$var = 1;
		$vars = array();

		// Create the UPDATE clause
		$set = "";
		$where = "";
		$data = get_object_vars($this);
		foreach( $cols as $name => $definition ){
			$val = $data[$name];
			$sqlVal = $definition->sqlOf($val);
			if( $definition->isPrimaryKey() ){
				if (strlen($where) > 0) $where .= " AND ";
				$where .= $definition->getName() . " = " . $sqlVal;
			}
			else if( $definition->isVersion() ){
				if (strlen($where) > 0) $where .= " AND ";
				$where .= $definition->getName() . " = " . $sqlVal;
				if( strlen($set) > 0 ) $set .= ", ";
				$set .= $definition->getName() . " = " . $definition->getName() . " + 1";
				$versionColumn = $name;
			}
			else if( !$definition->isAutomatic() && !is_null($sqlVal) ) {
				if( strlen($set) > 0 ) $set .= ", ";
				$set .= $definition->getName() . " = " . $sqlVal;
			}
		}
		$table = $this->getTableName();
		$sql = "UPDATE $table SET $set WHERE $where;";

		$nb = $this->dao->execute($sql);
		if( $nb > 1 ){
			$ret = false;
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
				// Update the version
				$this->$versionColumn++;
			}
		}
		return $ret;
	}


	protected function getPrimaryWhere() {
		// Create the WHERE clause
		$vars = array();
		$primary = $this->getPrimary();
		//$definitions = $this->getColumns();
		$where = "";
		$data = get_object_vars($this);
		foreach( $primary as $prop => $definition ){
		$val = $data[$prop];
		if( !isset($val) ){
			fatalError("SQL", "Missing value for column $k for entity " . $this->getTableName() . ".");
		}
			 
		if (strlen($where) > 1 ) $where .= " AND ";
			$where .= $definition->getName() . " = " . $definition->sqlOf($val);
		}
		return $where;
  	}
  	 
  	/**
  	 * Delete rows in the entity based on the
	 * where clause given as parameter.
	 *
	 * @param unknown $where the rows to delete
	 * @return the request for deleteing.
	 */
	static public function getDeleteQuery( $where ){
		$whereClause = static::getWhereClause( $where );
		$table = static::getTableName();
		$sql = " DELETE FROM $table $whereClause;";
		// $nb = static::dbexecute($sql);
		return $sql;
	}
			 
	/**
	 * Delete the current instance.
	 *
	 */
	public function delete(){
		$where = $this->getPrimaryWhere();
		$sql = static::getDeleteQuery($where);
		$nb = $this->dao->execute($sql);
		if( $nb != 1 ){
			$dao->logQuery($sql, "Delete of $nb rows instead of one." );
		}
	}

	/**
	 * Reload from the database.
	 * 
	 * @return NULL|\Concerto\Database\DBEntity the entity of NULL if
	 * 		deleted.
	 */
	public function reload() {
		$where = $this->getPrimaryWhere();
		$row = $this->selectUnique($where);
		return $row;
	}

	
	/**
	 * Same as the select() function but returns only the first
	 * line or null if nothing found. Throws an exception if several
	 * instances are returned.
	 * 
	 * @param string a WHERE condition.
	 * @return null|DBEntity the entity or NULL if not found.
	 *
	 */
	public function selectUnique( $where ){
		 $rows = $this->select($where);
		 $nb = count($rows);
		 if( $nb == 0 ){
			 return null;
		 }
		 else if( $nb == 1 ){
			 return $rows[0];
		 }
		 throw new DAOException("Too many lines returned.");
	}

	
	public function getSqlUnique( $query ) {
		$rows = $this->dbquery($query);
		$results = null;
		foreach ( $rows as $row ){
			$results = $row[0];
		}
		return $results;
	}

	/**
	 * Get data (an unique data from SQL).
	 * 
	 * @param string $sql_col the SQL column
	 * @param string $where the filtering
	 * @return NULL|unknown
	 */
	public function getData( $sql_col, $where ) {
		$whereClause = static::getWhereClause( $where );
		$query = "SELECT $sql_col"
			. " FROM " . static::getTableName()
			. " " . $whereClause;
		$results = $this->getSqlUnique($query);
		return $results;
	}

	/**
	* Retrieve the number of elements.
	*
	* @param string $where the filtering.
	* @return the first column of the first row or null if
	* 	no data retrieved.
	*/
	public function count( $where = null ){
// 		$whereClause = static::getWhereClause( $where );
// 		// Now select data
// 		$sql = "SELECT COUNT(*)"
// 			. " FROM " . static::getTableName()
// 			. " " . $whereClause;
// 		$results = static::getSqlUnique($sql);
		return $this->getData( 'COUNT(*)', $where);
	}

	public static function sql2entity( $definitions, $data ){
		$obj = new static();
		foreach( $definitions as $prop => $def ){
			$val = $data[$def->getName()];
			if( isset( $val) ){
				$obj->$prop = $def->fromSql($val);
			}
			else {
				// Set to NULL to avoid issues on non definied properties.
				$obj->$prop = null;
			}
		}
		return $obj;
	}

	/**
	 * Creates an ORDER clause ("ORDER BY...") based on
	 * associative array.
	 * 
	 * @param array $order an associative array with the
	 * 		property as key and +1 or -1 as value (depending
	 * 		if you want to sort ascending or descending).
	 * 
	 * @return string  the SQL ORDER clause or an empty string.
	 */
	static public function getOrderClause( $order = null ){
		$clause = "";
		if( $order && is_array($order) ){
			$definitions = static::getColumns();
			foreach( $order as $prop => $v ){
				if( strlen($clause) > 0 ) $clause .= ", ";
				$def = $definitions[$prop];
				$clause .=  $def->getName();
				if( $v < 0 ) $clause .= " DESC";
				else $clause .= " ASC";
			}
			$clause = "ORDER BY $clause";
		}

		return $clause;
	}

	/**
	 * Get the SQL where clause.
	 * 
	 * @param string|array $where the where clause
	 * 		if SQL or an array 
	 */
	static public function getWhereClause( $where = null ){
		$clause = "";
		if( $where ){
			if( is_array($where) ) {
				$definitions = static::getColumns();
				// Make a suitable WHERE clause
				foreach ( $where as $prop => $val ){
					$def = $definitions[$prop];
					$clause .= (strlen($clause) > 0 ? " AND " : "WHERE ");
					if( $val === NULL ){
						// Special case
						$clause .= $def->getName() . ' IS NULL';
					}
					else {
						$clause .= $def->getName() . ' = ' . $def->sqlOf($val);
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
	 * Select entities based on a where clause.
	 *
	 * @param string|array $where the restrictions to apply
	 * @param array $order the ordering if apply.
	 * 
	 * @return array an array of DBEntity objects.
	 */
	public function select( $where = null, $order = null ){
		$whereClause = static::getWhereClause( $where );
		$orderClause = static::getOrderClause( $order );

		// Now select data
		$sql = "SELECT * FROM " . static::getTableName()
				. " " . $whereClause
				. " " . $orderClause;
		$results = $this->selectByQuery($sql);
		return $results;
	}

	/**
	 * SELECT in the database based on a plain SQL request.
	 * 
	 * @param string $query the SQL request.
	 * @return DBEntity[] an array containing the entities.
	 */
	public function selectByQuery( $query ){
		$rows = $this->dbquery($query);

		// Return the results in an array
		$definitions = $this->getColumns();
		$results = [];
		if( $rows ){
			// In case of no data, the result set is simply NULL
			foreach( $rows as $data ){
				$results[] = $this->sql2entity( $definitions, $data );
			}
		}
		return $results;
	}

// 	public static function dbexecute( $sql ){
// 		$db = SQL::getConnection();
// 		$ret = $db->execute($sql);
// 		return $ret;
// 	}


	/**
	 * Converts a SQL result in an entity.
	 *
	 * @param array $row a single row obtained through the $db->query() call.
	 * @return DBEntity the entity.
	 */
	public static function fromSqlRow($row){
		return static::sql2entity( static::getColumns(), $row);
	}

	// 	static public function getAll(){
	// 		// It is trivial to retrieve the data.
	// 		$primary = static::getPrimary();
	// 		$results = static::select();
	// 		$entities = array();
	// 		foreach( $results as $row ){
	// 			$key = "";
	// 			foreach( $primary as $k => $def ){
	// 				if( strlen($key) > 0 ) $key .= ",";
	// 				$key .= $k;
	// 			}
	// 			$entities[$key] = $row;
	// 		}
	// 		return $entities;
	// 	}

	/**
	 *	This function is defined to retrieve
	 *	entities having an unique (technical) identifier
	 *	name "id". This is an
	 */
	 static function getById( $id ){
		$keys = self::getPrimary();
		$where = array();
		foreach( $keys as $key => $col ){
			$where[$key] = $id;
		}
		$unique = $this->selectUnique( $where );
		return $unique;
	}

	public function __toString() {
		$cols = static::getColumns();
		$ret = "[" . get_class($this) . ":";
		$nbCols = 0;
		$label = "";
		foreach( $cols as $key => $col ){
			if( $col->isPrimaryKey() ){
				if( $nbCols > 0 ) $ret .= "/";
				$val = $this->get( $key );
				if( is_null($val) ){
					$ret .= "<null>";
				}
				else {
					$ret .= $val;
				}
			}
			else if( $col->isLabel() ){
				$val = $this->get( $key );
				if( !is_null($val) ) $label = " ($val)";
			}
		}
		$ret .= "$label]";
		return $ret;
	}

	/**
	 * Lazy loading of objects based on foreign keys.
 	 *
	 * @param unknown_type $property
	 */
	public function __get( $name ){
		$found = false;
		$ret = null;
		$cols = $this->getColumns();
		foreach( $cols as $k => $c ){
			if( $c->isForeignKey() ){
				if( $name === $c->foreignKeyName() ){
					$found = true;
					$ret = call_user_func( $c->foreignKeyTable() . "::getById", $this->$k );
				}
			}
		}
		if( !$found ){
			trigger_error('Undefined property via __get(): ' . $name, E_USER_NOTICE);
		}
		return $ret;
	}

	
	public function dataFromQuery( $sql ){
		$rows = $this->dbquery($sql);
		$ret = array();
		if( $rows ){
			$cols = static::getColumns();
			foreach( $rows as $row ){
				$ret[] = static::sql2entity($cols, $row);
			}
		}
		else {
			trigger_error("Technical Error - Please send an email to your maintenance team (SQL=$sql).");
		}
		return $ret;
	}

	
// 	/**
// 	 * Clean of the text for real text output. Based
// 	 * on rules about encoding URLs.
// 	 *
// 	 * TODO: this method should move to std class.
// 	 *
// 	 * @param string $str the text to encode.
// 	 * @return string an encoded text without punctuation
// 	 * 		but accents and UTF8 characters are kept
// 	 * 		for a better view on search engines.
// 	 */
// 	public static function clean_text($str){
// 		$clean = '';
// 		$clean = mb_strtolower($str, 'UTF-8' );
// 		// $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
// 		$clean = preg_replace("/[?\/\\&+'\"!,;:.()]/", ' ', $clean);
// 		$clean = preg_replace("/ +/", ' ', $clean);
// 		$clean = trim($clean);
// 		$clean = str_replace(' ', '-', $clean);
// 		$clean = urlencode($clean);
// 		if( !$clean ) $clean = 'empty'; // avoid empty data
// 		return $clean;
// 	}
};

