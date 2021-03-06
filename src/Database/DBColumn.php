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
	const GROUP = 7;
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
    const PASSWORD = 64;  // Consider password (only informative)
    
	private $columnName;
	private $columnType;
	private $columnPrecision;
	private $columnStatus;
	private $description;
	private $foreignKeyName;
	private $foreignTableName;
	private $label;
	private $options;
	
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
		$this->options = $stuff;
		
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

		if( $type == self::BOOLEAN && !@$stuff['booleans']){
			$this->options['booleans'] = ['YES', 'NO', ''];
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

	public function isPassword(){
		return (($this->columnStatus & self::PASSWORD) == self::PASSWORD);
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

	/**
	 * Checks if the column is a date (or a DATETIME).
	 * 
	 * @return TRUE if the column is DATE or DATETIME,
	 * FALSE in all other cases.
	 */
	public function isDate() {
		switch( $this->columnType ){
			case self::DATE :
			case self::DATETIME :
				return TRUE;
	
			default :
				return FALSE;
		}
	}
	
	/**
	 * Checks if the column is a group.
	 *
	 * @return TRUE if the column is a group,
	 * FALSE in all other cases.
	 */
	public function isGroup() {
		switch( $this->columnType ){
			case self::GROUP :
				return TRUE;
	
			default :
				return FALSE;
		}
	}
	
	/**
	 * Convert a Boolean to a string.
	 * 
	 * @param boolean a boolean value or NULL.
	 * @return string the string for display.
	 * 
	 */
	public function boolean2string($val){
		$ret = '';
		if( $val === TRUE){
			$ret = @$this->options['booleans'][0];
		}
		else if( $val === FALSE){
			$ret = @$this->options['booleans'][1];
		}
		else if( $val ){
			$ret = @$this->options['booleans'][2];
		}
		return $ret;
	}

	/**
	 * Checks if the column is a boolean.
	 *
	 * @return TRUE if the column is a boolean,
	 * FALSE in all other cases.
	 */
	public function isBoolean() {
		switch( $this->columnType ){
			case self::BOOLEAN :
				return TRUE;
	
			default :
				return FALSE;
		}
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

	public function getModifierAsString(){
		$ret = []; 
		if( $this->columnStatus & self::NOT_NULL ){
			$ret[] = "NOT NULL";
		}
		if( $this->columnStatus & self::FOREIGN ){
			$ret[] = "FOREIGN KEY";
		}
		
		
		if( $this->columnStatus & self::VERSION == self::VERSION){
			$ret= [ "VERSION" ];
		}
		if( $this->columnStatus & self::IDENTITY == self::IDENTITY){
			$ret= [ "IDENTITY" ];
		}
		
		return implode(", ", $ret);
	}
 
    /**
     * Return the type of the column as a string.
     * 
     * @return the type of the column.
     */
    public function getTypeAsString(){
    	$type = "?"; // Image the worst case
    	switch( $this->columnType){
    		case DBColumn::VARCHAR:
    			$type = "VARCHAR";
    			break;
    			
    		case DBColumn::INTEGER:
    			$type = "INTEGER";
    			break;
  			case DBColumn::DATE:
  				$type = "DATE";
  				break;
			case DBColumn::DATETIME:
 				$type = "DATETIME";
 				break;
			case DBColumn::BOOLEAN:
				$type = "BOOLEAN";
				break;
			case DBColumn::NUMERIC:
				$type = "NUMERIC";
				break;
			case DBColumn::TEXT:
				$type = "TEXT";
				break;
			case DBColumn::GROUP:
				$type = "ARRAY";
				break;
    		default:
    			break;
    	}
    	if( $this->columnPrecision ){
    		$type .= "({$this->columnPrecision})";
    	}
    	return $type; 
    }
    
    
    /**
     * Returns the type of the column.
     * 
     * @return int the type
     */
    public function getType() {
    	return $this->columnType;
    }
}

