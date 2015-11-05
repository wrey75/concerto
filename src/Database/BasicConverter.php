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
	public function strsql( $str ){
	 	if( strlen($str) == 0 ) {
			// Empty string generates a NULL string
			return "NULL";
		}
		$ret = str_replace( "'", "''", $str );
		return "'$ret'";
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
		return $this->sqlstr( date("C", $ts) );
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
}