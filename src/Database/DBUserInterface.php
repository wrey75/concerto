<?php

namespace Concerto\Database;

use Concerto;
use Concerto\std;
use Concerto\DataTable;
use Concerto\Form\BootstrapForm;

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
		$this->entity = new $name();
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
// 				else if( $col->isDate() ){
//					Dates are NOT timestamp and cabe in the past (before 1970).
// 					$info .= "|date";
// 				}
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
		$rows = $this->dao->select($this->entity, $where);
		$columns = $this->entity->getColumns();
		$tbl->setColumns( $this->dataTableColumns($hidden) );
		echo $tbl->getHeader();
        foreach( $rows as $row ) {
        	$data = [];
        	foreach( $columns as $prop => $col ){
        		$val = $row->$prop;
        		if( $col->isDate() ){
        			if( $val ){
        				$data["{$prop}-order"] = $val->getTimestamp();
        				$ret = $val->format(($col->getType() == DBColumn::DATETIME) ? "c" : "Y-m-d");
        				$data[$prop] = preg_replace( "/\+(.*)/", "<small>+$1</small>", str_replace( 'T',' ', $ret) );
        			}
        			else {
        				$data["{$prop}-order"] = 0;
        				$data[$prop] = '';
        			}
        		}
        		else if( $col->isGroup() ){
        			$data[$prop] = implode(", ", $val);
        		}
        		else if( $col->isBoolean() ){
        			$data[$prop] = $col->boolean2string($val);
        		}
        		else {
        			$data[$prop] = $val;
        		}
        		
        		if( $col->isPassword()){
        			$val = substr($data[$prop], 0, 12);
        			$ext = "";
        			for( $i = 0; $i < strlen($val); $i++ ){
        				$ext .= "*";
        			}
        			$data[$prop] = $ext;
        		}
        	}
        	
    		echo $tbl->getRow( $data );
    	}
    	echo $tbl->getFooter();
	}


	/**
	 * Display all the stuff based on pure SQL
	 * request.
	 * 
	 * @param string $sql the query.
	 */
	public function showDataFromSQL( $query )
	{
		$tbl = new Concerto\DataTable;
		
		if( std::beginsWith($query, '?') ){
			$sql = "SELECT * FROM " . substr($query,1);
		}
		else {
			$sql = $query;
		}
		
		$rs = $this->dao->query($sql);
		if( $rs ){
			$tableColDef = [];
			$nb_cols = $rs->columnCount();
			for($i = 0; $i < $nb_cols; $i++){
				$info = $rs->getColumnMeta($i);
				$tableColDef[ "col_$i" ] = $info['name'];
			}
			$tbl->setColumns($tableColDef);
			echo $tbl->getHeader();			
		
			// Show data
			foreach( $rs as $row ) {
				$data = [];
				for($i = 0; $i < $nb_cols; $i++){
					$data[ "col_$i" ] = $row[$i];
				}
				echo $tbl->getRow( $data );
			}
			echo $tbl->getFooter();
		}
		else {
			$tbl->setColumns([
					'state' => 'SQL State',
					'code' => 'SQL Error Code',
					'message' => 'SQL Error message',
			]);
			echo $tbl->getHeader();
			echo $tbl->getRow( $this->dao->getLastError() );
			echo $tbl->getFooter();
		}
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
				"modifier" => "Specials",
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
			$data["modifier"] = $col->getModifierAsString();
			echo $tbl->getRow( $data );
		}
		echo $tbl->getFooter();
	}
	
	/**
	 * Process the form.
	 * 
	 */
	public function processForm() {
		if( @$_REQUEST['_entity'] ){
			echo "*** {$_REQUEST['_entity']} ***\n";
		}
	}
	
	/**
	 * Show the form.
	 * 
	 * @param DBEntity $obj the enity to edit.
	 *
	 */
	public function showForm($obj) {
		$form = new BootstrapForm($obj);
		$ret = $form->open();
		
		$columns = $obj->getColumns();

		foreach( $columns as $k=>$col ) {
			$read_only = ( $col->isSequence() || $col->isAutomatic() );
			
			$label = $this->getLabel($k, $col);
			$ret .= $form->new_group($k);
			$ret .= $form->label($label);
			$placeholder = null;
			
			if( $read_only ){
				if( $col->isSequence() && !$obj->_isPersistent ){
					$placeholder = "The ID will be set by the database.";
				}
				$ret .= $form->input_disabled( $k, $placeholder );
			}
			else if( $col->getType() == DBColumn::VARCHAR ){
				// A simple input
				$ret .= $form->input_text( $k );
			}
			else if( $col->getType() == DBColumn::INTEGER || $col->getType() == DBColumn::NUMERIC ){
				// A simple input
				$ret .= $form->input_number( $k );
			}
			$data = [];
			$data["property"] = $k;
			$data["colname"] = $col->getName();
			$data["desc"] = $col->getDescription();
			$data["label"] = $this->getLabel($k,$col);
			$data["type"] = $col->getTypeAsString();
			$ret .= "<hr>\n";
		}
		
		$ret .= $form->hidden("_entity", get_class($obj));
		
		$buttons = [
			'submit' => ($obj->_isPersistent ? "Update" : "Insert")
		];
		if( $obj->_isPersistent ){
			$buttons['delete'] = "Delete";
		}
		
		$ret .= $form->submit_buttons( $buttons );

		$ret .= $form->close();
		echo $ret;
	}
	
}
