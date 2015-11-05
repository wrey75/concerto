<?php

namespace Concerto\Database;

use Concerto;
use Concerto\std;

/**
 * A class to simplify the CRUD interface.
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
	 */
	protected function dataTableColumns(){
		$ret = [];
		$cols = $this->entity->getColumns();
		foreach( $cols as $k => $col ){
			$info = std::capitalizeFirst($k);
			if( $col->getLabel() ){
				$info = $col->getLabel();
			}

			if( $col->isNumeric() ){
				$info .= "|numeric";
			}
			
			$ret[$k] = $info;
		}
		return $ret;
	}

	/**
	 * Shows the data included in the table. You should
	 * use the 
	 * @param unknown $where
	 */
	public function showTable( $where = null ) {
		$tbl = new Concerto\DataTable;

		$rows = $this->entity->select($where);
		
		$tbl->setColumns( $this->dataTableColumns() );
		
		echo $tbl->getHeader();
        foreach( $rows as $row ) {
        	$data = (array)$row;
        	echo "<!-- {$data['id']} -->\n";
    		echo $tbl->getRow( $data );
    	}
    	echo $tbl->getFooter();
	}
	
}
