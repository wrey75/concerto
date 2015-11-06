<?php

namespace Concerto\Database;

use Concerto;
use Concerto\std;
use Concerto\DataTable;

/**
 * A class to simplify the CRUD interface. Note that
 * class relies on the Bootstrap CSS and the DataTable
 * class for the rendering.
 * 
 * The current class cane:
 * <ul>
 *   <li>display data</li>
 *   <li>display the table structure</li>
 *   <li>modify an item</li>
 * </ul>
 * 
 * @author wre
 *
 */
class DBUserInterface {
	
	private $dao;
	private $entity;
	
	/**
	 * Create a new User Interface for
	 * an entity.
	 * 
	 * @param DBEntity $entityClass the entity class.
	 * @param DAO $dao the DAO.
	 * 
	 */
	public function __construct( $name, $dao = null ){
		if( !$dao ){
			// Use the default DAO.
			$dao = DAO::getDefault();
		}
		$this->dao = $dao;
		$this->entity = new $name($dao);
		if( !is_a($this->entity, 'Concerto\Database\DBEntity' )){
			throw new \Exception('$name: Not a subclass of ' . Concerto\Database\DBEntity );
		}
	}

	/**
	 * Convert the columns of the table in an array
	 * describing the columns in a compatible way for 
	 * output.
	 * 
	 * @param hidden the columns in this array will be not
	 * shown.
	 * @return the array of columns suitable for DataTable
	 * objects.
	 */
	protected function dataTableColumns($hidden){
		$ret = [];
		$cols = $this->entity->getColumns();
		foreach( $cols as $k => $col ){
			if( !in_array($k, $hidden) ){
				$info = $this->getLabel($k, $col);
	
				if( $col->isNumeric() ){
					$info .= "|numeric";
				}
				
				$ret[$k] = $info;
			}
		}
		return $ret;
	}


	/**
	 * Shows the data included in the table. You should
	 * use the BOOTSTRAP capabilities.
	 * 
	 * @param array $hidden the columns you DO NOT want to see
	 * (to protect some confidential information or because of the 
	 * number of columns).
	 * @param string|array $where the filter for the
	 * data. All the data displayed if not specified.
	 */
	public function showData( $hidden = [], $where = null ) {
		$tbl = new Concerto\DataTable;

		$rows = $this->entity->select($where);
		
		$tbl->setColumns( $this->dataTableColumns($hidden) );
		echo $tbl->getHeader();
        foreach( $rows as $row ) {
        	$data = (array)$row;
    		echo $tbl->getRow( $data );
    	}
    	echo $tbl->getFooter();
	}
	
	/**
	 * Retrieve or compute the label of the column.
	 * 
	 * @param string $k the key (property name) of the column.
	 * @param DBColumn $col the column itself
	 * @return the label for this column.
	 * 
	 */
	protected function getLabel($k,$col){
		$info = std::capitalizeFirst($k);
		if( $col->getLabel() ){
			$info = $col->getLabel();
		}
		return $info;
	}
	
	/**
	 * Shows the structure of the table. Relies on
	 * the entity itself and the columns defined.
	 * 
	 */
	public function showStructure() {
		$tbl = new Concerto\DataTable;
	
		$tbl->setColumns( [
				"property"=> "Property",
				"colname" => "Column name",
				"type" => "Type",
				"label" => "Label",
				"desc" => "Description"
		] );
	
		echo $tbl->getHeader();
		$columns = $this->entity->getColumns();
		foreach( $columns as $k=>$col ) {
			$data = [];
			$data["property"] = $k;
			$data["colname"] = $col->getName();
			$data["desc"] = $col->getDescription();
			$data["label"] = $this->getLabel($k,$col);
			$data["type"] = $col->getTypeAsString();
			echo $tbl->getRow( $data );
		}
		echo $tbl->getFooter();
	}
	
	public function showForm($value) {
		
	}
	
}
