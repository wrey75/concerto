<?php

namespace \Concerto;

class MysqlDriver {

	/**
	 * Convert a value from the database
	 * to a compatible type in PHP. NOTE:
	 * the dates and times are translqted to
	 * timestamps.
	 *
	 */
	public function fromSql( $value ){
		if( !isset($value) ) return NULL;
		switch( $this->columnType ){
			case self::VARCHAR :
				return $value;
	
			case self::INTEGER :
				return intval($value);
					
			case self::DATE :
			case self::DATETIME :
				return new DateTime( $value );
	
			case self::NUMERIC :
				return $value;
					
			case self::BOOLEAN :
				return ($value == 'Y' ? TRUE : FALSE);
	
			default:
				throw new Exception("Unknown SQL TYPE: " . $this->columnType);
		}
	}
	
	public function sqlOf( $value ){
		if( $value === null ) return 'NULL';
		switch( $this->columnType ){
			case self::VARCHAR :
				$precision = $this->columnPrecision;
				if( $precision && (strlen($value) > $precision) ){
					// The length is exceeded
				}
				return SQL::sqlstr($value);
	
			case self::INTEGER :
				return intval($value);
					
			case self::DATE :
				return SQL::sqldate( $value );
				break;
	
			case self::DATETIME :
				return SQL::sqltime( $value );
				break;
	
			case self::NUMERIC :
				return (is_numeric( $value ) ? $value : 'NULL' );
					
			case self::BOOLEAN :
				return SQL::sqlstr($value ? 'Y' : 'N');
	
			default:
				throw new Exception("Unknown SQL TYPE: " . $this->columnType);
		}
	}
	
}