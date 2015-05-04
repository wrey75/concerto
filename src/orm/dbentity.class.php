<?php

namespace \Concerto;

include_once dirname(__FILE__) . '/klogger.inc.php';


include dirname(__FILE__) . '/sql.inc.php';
include dirname(__FILE__) . '/dbcolumn.inc.php';

abstract class DBEntity {

	const COLNAME = 0;
	const SQLTYPE = 1;

	/**
	 * Must return the table name of this
	 * entity
	 */
	public static function getTableName() {
		throw new Exception("DBEntity::getTableName() must be implemented by you entity class.");
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
	public static function dbquery( $sql ){
		$dao = DataAccessObject::getDefault();
		$rows = $dao->query($sql);
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
	public function insert() {
		$dao = DataAccessObject::getDefault();

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

		$rows = $db->execute($sql);
		if( $rows < 1 ){
			$db->error("NOTHING INSERTED: $sql");
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
	 * @param $object the object to save on the database.
	 * @param $primary the primary key for the object.
	 *   If the primary key is made of several column, the
	 *   columns must be separated by a coma without space.
	 */
	public function update() {
		$ret = true;
		$db = SQL::getConnection();
			
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

		$nb = static::dbexecute($sql);
		if( $nb > 1 ){
			$ret = false;
			$log = KLogger::getDefault();
			$errs = $db->errorInfo();
			$message .= "\nSQL: $sql\nError code: $errs[0]\nError message: $errs[2]";
			$log->error( $message );
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
  	* delete rows in the entity based on the
			* where clause given as parameter.
			*
			* @param unknown $where the rows to delete
			* @return number the number of deleted rows
			*/
			static public function deleteRows( $where ){
			$db = SQL::getConnection();

			$whereClause = static::getWhereClause( $where );
			$table = static::getTableName();
			$sql = " DELETE FROM $table $whereClause;";
			$nb = static::dbexecute($sql);
			return $nb;
			}
			 
			/**
			* Delete the current instance.
			*
			*/
			public function delete(){
			$db = SQL::getConnection();

			$where = $this->getPrimaryWhere();
			$nb = static::deleteRows($where);
			if( $nb != 1 ){
			fatalError("SQL", "Delete of $updated rows instead of one: $sql\n", $vars );
			}
			}

			public function reload() {
			$where = $this->getPrimaryWhere();
			$row = self::selectUnique($where);
				return $row;
			}

			/**
			* Same as the select() function but returns only the first
				* line or null if nothing found. Throws an exception if several
				 * instances are returned.
				 *
				 */
				 public static function selectUnique( $where ){
				 $rows = static::select($where);
				 $nb = count($rows);
				 if( $nb == 0 ){
				 return null;
				 }
				 else if( $nb == 1 ){
				 return $rows[0];
				 }
				 throw new Exception("Too many lines returned");
			}

	public static function getSqlUnique( $query ) {
	$rows = static::dbquery($query);
	$results = null;
	foreach ( $rows as $row ){
	$results = $row[0];
	}
	return $results;
	}

	public static function getData( $sql_col, $where ) {
	$whereClause = static::getWhereClause( $where );
	$query = "SELECT $sql_col"
	. " FROM " . static::getTableName()
	. " " . $whereClause;
	$results = static::getSqlUnique($query);
	return $results;
	}

	/**
	* Retrieve the number of elements.
	*
	* @param string $where the filtering.
	* @return the first column of the first row or null if
	* 	no data retrieved.
	*/
	public static function count( $where = null ){
	$whereClause = static::getWhereClause( $where );
	// Now select data
	$sql = "SELECT COUNT(*)"
	. " FROM " . static::getTableName()
	. " " . $whereClause;
			$results = static::getSqlUnique($sql);
			return $results;
	}

	static public function sql2entity( $definitions, $data ){
	$obj = new static();
	foreach( $definitions as $prop => $def ){
		$val = $data[$def->getName()];
		if( isset( $val ) ){
		$obj->$prop = $def->fromSql($val);
		}
		else {
		// Set to NULL to avoid issues on non definied properties.
		$obj->$prop = null;
		}
		}
			return $obj;
		}

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

		static public function getWhereClause( $where = null ){
			$clause = "";
			if( $where ){
			if( is_array($where) ) {
			$definitions = static::getColumns();
			// Make a suitable WHERE clause
			$clause = "";
			foreach ( $where as $prop => $val ){
			$def = $definitions[$prop];
			if( strlen($clause) > 0 ) $clause .= " AND ";
			else $clause = "WHERE ";
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
			* @param unknown_type $where
			*/
			public static function select( $where = null, $order = null ){
			$log = KLogger::getDefault();

			$whereClause = static::getWhereClause( $where );
			$orderClause = static::getOrderClause( $order );

				// Now select data
				$sql = "SELECT * FROM " . static::getTableName()
				. " " . $whereClause
				. " " . $orderClause;
				$results = static::selectByQuery($sql);
		return $results;
			}

			public static function selectByQuery( $query ){
			$rows = static::dbquery($query);

			// Return the results in an array
			$definitions = static::getColumns();
			$results = array();
			if( $rows ){
			// In case of no data, the result set is simply NULL
			foreach( $rows as $data ){
			$results[] = static::sql2entity( $definitions, $data );
			}
			}
			return $results;
			}

			public static function dbexecute( $sql ){
			$db = SQL::getConnection();
			$ret = $db->execute($sql);
			return $ret;
}



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
	 static public function getById( $id ){
	 $keys = self::getPrimary();
	 $where = array();
	 foreach( $keys as $key => $col ){
	 $where[$key] = $id;
		}
		$unique = static::selectUnique( $where );
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
		trigger_error(
		'Undefined property via __get(): ' . $name,
		E_USER_NOTICE);
}
return $ret;
}

public static function dataFromQuery( $sql ){
$rows = static::dbquery($sql);
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

	/**
	* Clean of the text for real text output. Based
			* on rules about encoding URLs.
			*
			* TODO: this method should move to std class.
			*
			* @param string $str the text to encode.
			* @return string an encoded text without punctuation
				* 		but accents and UTF8 characters are kept
				* 		for a better view on search engines.
				*/
				public static function clean_text($str){
				$clean = '';
				$clean = mb_strtolower($str, 'UTF-8' );
				// $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
				$clean = preg_replace("/[?\/\\&+'\"!,;:.()]/", ' ', $clean);
				$clean = preg_replace("/ +/", ' ', $clean);
		$clean = trim($clean);
		$clean = str_replace(' ', '-', $clean);
		$clean = urlencode($clean);
		if( !$clean ) $clean = 'empty'; // avoid empty data
		return $clean;
		}
		};

		?>