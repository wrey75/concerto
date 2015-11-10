<?php

namespace Concerto\Database;

/**
 * Describes an entity. An entity is a simple
 * description containing properties.
 * 
 * Note you must create a class that extends the DBEntity. The
 * simplest is to create the new class with the getColumns()
 * and getTableName() implementations.
 * 
 * Once you have done this, a good idea is to create the properties
 * as public variables. It is only for IDE editors.
 * 
 * By definition, a function named validateXXX() where "XXX" is
 * the property name can be used to validate the field. This
 * is used by the DBUserInterface class when inserting,
 * updating.
 * 
 * @author wre
 *
 */
abstract class DBEntity {
	
	/**
	 * Set to TRUE if the record is persistent (that means the
	 * record has been persisted through INSERT or has been 
	 * retrieved from the database).
	 * 
	 * @var bool
	 */
	public $_isPersistent = false; 
	
	/**
	 * The DAO which retrieved this entity. Used for retrieving
	 * the objets linked through a foreign key. The method
	 * involded need to store the dao to be able to load 
	 * in a lazy mode.
	 * 
	 * NOTE: the foreign key loading is not available now. 
	 * 
	 * @var DAO
	 * 
	 */
	public $_dao = null;
	
	
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
	 * This method returns the columns
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
	 * @return an associative array having property name
	 * as key, the column definition as value.
	 *
	 */
	public static function getPrimaryColumns(){
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
	 * @deprecated use getPrimaryColumns() instead.
	 */
	public static function getPrimary(){
		return static::getPrimaryColumns();
	}

	
	/**
	 * Return the value stored in the entity
	 * based on the column name. Used to easily
	 * access values dynamically.
	 */
	public function get($name) {
		$arr = get_object_vars($this);
		return $arr[ $name ];
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


	/**
	 * Create a "fake" object. This is a helper to create
	 * a new instance of the same entity than the original.
	 * This method is not intended 
	 * 
	 */
	public static function newInstance(){
		return new static();
	}
	
};

