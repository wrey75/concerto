<?php

namespace Concerto\Database;

/** 
 * Converter from PHP to Database and vice-versa.
 * 
 * @author wre
 *
 */
class BasicConverter {
	
	/**
	 * Makes the string compatible with SQL to
	 * avoid both SQL injections and SQL syntax
	 * errors.
	 * 
	 * The converter use the SQL-92 rule: if there
	 * are simple quotes, we double them.
	 *
	 * @param $str the string to put as SQL compatible.
	 * 	Note that zero-length strings and NULL values
	 * 	are translated as "NULL".
	 */
	public function sqlstr( $str ){
		$str = strval($str);
		if( strlen($str) == 0 ) {
			// Empty string generates a NULL string
			return "NULL";
		}
		$ret = str_replace( "'", "''", $str );
		return "'$ret'";
	}
	
	/**
	 * 
	 * @deprecated use sqlstr() instead.
	 * 
	 */
	public function strsql( $str ){
		return $this->sqlstr($str);
	}
	
	
	/**
	 * Convert an integer to a SQL value.
	 * 
	 * @param int $val the value to convert
	 * @return string the converted value.
	 */
	public function sqlint( $val ){
		if( !$val && $val !== 0 ){
			// NULL value generates a NULL string
			return "NULL";
		}
		$ret = "" . intval($val); // force the convert (in case)
		return $ret;
	}
	
	/**
	 * Returns the current date and time in a SQL
	 * compatible way. Classes can override with their
	 * own implementation (usually their own way to get
	 * the current time).
	 * 
	 * To be generic, the date is returned using the
	 * sqldatetime() method.
	 * 
	 * @return the current expressed to be database
	 * 	compatible.
	 *
	 */
	public function sqlnow(){
	 	return $this->sqldatetime(time());
	}
	
	
	/**
	 * Convert a DateTime (or a timestamp) into a SQL
	 * DATETIME compatible value.
	 * 
	 * @param DateTime $ts the date to convert. NULL
	 * 		will use the current date.
	 */
	public function sqldatetime($ts){
		$ts = \Concerto\std::timestamp($ts);
		return $this->sqlstr( date("Y-m-d H:i:s", $ts) );
	}
	
	/**
	 * Convert a DateTime (or a timestamp) into a SQL
	 * DATE compatible value.
	 *
	 * @param DateTime $ts the date to convert. NULL
	 * 		will use the current date.
	 */
	public function sqldate($ts){
		$ts = \Concerto\std::timestamp($ts);
		return $this->sqlstr( date("Y-m-d", $ts) );
	}
	
	/**
	 * Convert a DateTime (or a timestamp) into a SQL
	 * DATE compatible value.
	 *
	 * @param DateTime $ts the date to convert. NULL
	 * 		will use the current date.
	 */
	public function sqltime($ts){
		$ts = \Concerto\std::timestamp($ts);
		return $this->sqlstr( date("H:i:s", $ts) );
	}
	
	

	/**
	 * Convert a value from the database
	 * to a compatible type in PHP. 
	 * 
	 * NOTE:
	 * the dates and times are translated to
	 * DateTime.
	 * 
	 * @param DBColumn $col the column.
	 * @return mixed $value the value to convert from SQL to basic.
	 * 
	 * @return the value converted.
	 *
	 */
	public function fromSql( $col, $value ){
		if( !isset($value) ) return NULL;
		switch( $col->getType() ){
			case DBColumn::VARCHAR :
				return $value;
	
			case DBColumn::INTEGER :
				return intval($value);
					
			case DBColumn::DATE :
			case DBColumn::DATETIME :
				return new \DateTime( $value );
	
			case DBColumn::NUMERIC :
				return $value;
					
			case DBColumn::BOOLEAN :
				return ($value == 'Y' ? TRUE : ($value == 'N' ? FALSE : NULL));
	
			case DBColumn::GROUP :
				return explode(',', $value);
				
			default:
				throw new SQLException("Unknown SQL TYPE: " . $col->getType());
		}
	}
	

	
	/**
	 * Convert some data to a SQL value.
	 * 
	 * @param DBColumn $col the column.
	 * @param mixed $value the data to convert.
	 * @throws SQLException if an exception occurred.
	 */
	public function sqlOf( $col, $value ){
		if( $value === null ) return 'NULL';
		switch( $col->getType() ){
			case DBColumn::VARCHAR :
				$precision = $col->getPrecision();
				if( $precision && (strlen($value) > $precision) ){
					// The length is exceeded
				}
				return $this->sqlstr($value);
	
			case DBColumn::INTEGER :
				return intval($value);
					
			case DBColumn::DATE :
				return $this->sqldate( $value );
				break;
	
			case DBColumn::DATETIME :
				return $this->sqldatetime( $value );
				break;
	
			case DBColumn::NUMERIC :
				return (is_numeric( $value ) ? $value : 'NULL' );
					
			case DBColumn::BOOLEAN :
				return $this->sqlstr($value === NULL ? null : ($value ? 'Y' : 'N' ) );
				
			case DBColumn::GROUP :
				return $this->sqlstr( implode(',', $value) );
	
			default:
				throw new SQLException("Unknown SQL TYPE: " . $this->columnType);
		}
	}
}

