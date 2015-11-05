<?php

namespace Concerto\Database;

/**
 * A Database colum. Describes a SQL column in the database.
 * 
 * The description is very important because it defines how
 * the data is processed.
 * 
 * 
 * @author wrey75 William Rey
 *
 */
class DBColumn {
	
	// The types
	const VARCHAR = 1;
	const INTEGER = 2;
	const DATE = 3;
	const DATETIME = 4;
	const BOOLEAN = 5;
	const NUMERIC = 6;
	const TEXT = 1; // Same as varchar
	
	// The mode flags.
	const PLAIN = 0;      // A basic column
	const SEQUENCE = 1;   // A sequence (a numerical increment in the database)
	const NOT_NULL = 2;   // The column is mandatory
	const UNIQUE = 4;     // The column is unique (typically a primary key)
	const IDENTITY = 7;   // Include NOT_NULL + UNIQUE + SEQUENCE
	const PRIMARY  = 6;   // Include NOT_NULL + UNIQUE
    const AUTOMATIC = 8;  // Data is automatically set by the database
    const VERSION = 10;   // Used for optimistic locks. Include NOT_NULL => versionning
    const LABEL = 16;     // Used for label
    const FOREIGN = 32;   // It is a Foreign key
    
	private $columnName;
	private $columnType;
	private $columnPrecision;
	private $columnStatus;
	private $description;
	private $foreignKeyName;
	private $foreignTableName;
	private $label;
	
	/**
	 * Declares a new column for the related table.
	 * 
	 * @param string $columnName the column name in the database.
	 * @param string $type       the column type
	 * @param number $precision  the precision in digits or length of the column (for VARCHARs and NUMRICs). Not currently
	 * 		used (given as information).
	 * @param number $status     the status of of column (see flags)
	 * @param string $desc       description of the column (can be NULL).
	 * @param array  $stuff      other stuff linked to the column (description and other).
	 */
	public function __construct( $columnName, $type = self::VARCHAR, $precision = 0, $status = 0, $desc = NULL, $stuff = [] ){
		$this->columnName = $columnName;
		$this->columnType = $type;
		$this->columnPrecision = $precision;
		$this->columnStatus = $status;
		$this->description = $desc;
		
		if( @$stuff['description'] ){
			// The description can overwrite the description passed
			// as parameter;
			$this->description = $stuff['description'];
		}

		if( @$stuff['label'] ){
			$this->label = $stuff['label'];
		}
		
		if( @$stuff['foreign_key'] ){
			$this->foreignKeyName = $stuff['foreign_key'];
		}
		if( @$stuff['foreign_table'] ){
			$this->foreignTableName = $stuff['foreign_table'];
		}

		
//     ************************************
//     OLD VERSION
//     ************************************
// 		// Foreign key (if apply)
// 		$this->foreignKeyName = NULL;
// 		$this->foreignTableName = NULL;
// 		if( $this->isForeignKey() ){
// 			if( strpos($this->$foreign, ":") > 0 ){
// 				list( $this->foreignTableName, $this->foreignKeyName ) = explode( ":", $foreign);
// 			}
// 			else {
// 				$this->foreignKeyName = $foreign;
// 			}
// 		}
	}

	
	/**
	 * Returns the description of the column.
	 * 
	 * @return string the description of the column.
	 */
	public function getDescription(){
		return $this->description;
	}
	
	/**
	 * Check if the column is a primary key for the entity.
	 * 
	 */
	public function isPrimaryKey(){
		return (($this->columnStatus & self::PRIMARY) == self::PRIMARY);
	}
	
	public function isLabel(){
		return (($this->columnStatus & self::LABEL) == self::LABEL);
	}

	/**
	 * Check if the column is a version number for optimistic locks.
	 *
	 */
	public function isVersion(){
		return ($this->columnStatus == self::VERSION);
	}

	public function isAutomatic(){
		return (($this->columnStatus & self::AUTOMATIC) == self::AUTOMATIC);
	}
	
	public function isSequence(){
		return (($this->columnStatus & self::SEQUENCE) == self::SEQUENCE);
	}
	
	public function isForeignKey(){
		return (($this->columnStatus & self::FOREIGN) == self::FOREIGN);
	}
	
	public function foreignKeyName(){
		return $this->foreignKeyName;
	}
	
	public function foreignKeyTable(){
		return $this->foreignKeyTable;
	}
	
	/**
	 * Get the column name.
	 * 
	 * @return the column name.
	 * 
	 */
	public function getName(){
		return $this->columnName;
	}

	public function isNumeric() {
		switch( $this->columnType ){
			case self::NUMERIC :
			case self::INTEGER :
				return TRUE;

			default :
				return FALSE;
		}
	}

	public function getPrecision() {
		return $this->columnPrecision;
	}
	
	public function getLabel() {
		return $this->label;
	}	

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
}

