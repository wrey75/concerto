<?php

namespace Concerto;


/**
 * This class creates table of data based on
 * any datasource.
 * 
 * This datatable is based on the JQuery plug-in.
 * The basic table creation used <table>s tags.
 * 
 * @author William Rey (c)2011
 * @version $Revision: 194 $
 *
 */
class DataTable {
	private $id;
	private $ajax;
	private $callback;
	private $headers;
	static public $options = array();
	private $opt = null;
	private $skip_script = false;
	private $functions = array();
	private $columns = array();
	
	static public $static_date_format = "dd/mm/yy"; 
	static public $static_decimal_char = ",";
	static public $static_thousand_char = "&nbsp;";
	
    // Separator used for columns
    public $separator = "|";
    public $waitingText = "";

	/**
	 * Construct the table
	 * -------------------
	 *
	 */	
	function __construct( $myOptions = array() ){
		$this->opt = array( 
						'sDom'=> "<'row'<'col-sm-12'<'pull-left hidden-xs'p><'pull-right'f><'clearfix'>>>t<'row'<'col-sm-12'<'pull-left'i><'pull-right'><'clearfix'>>>",
						'bPaginate' => true,
						"bLengthChange" => false,
						"pageLength" => 100,
						"aaSorting" => array()
						 );
		$this->opt = array_merge( $this->opt, static::$options );
		$this->opt = array_merge( $this->opt, $myOptions );
	
		// Create an unique identifier for the DataTable
		// object.	
		$this->id = uniqid("tbl");
		$this->date_format = static::$static_date_format;
		$this->decimal_char = static::$static_decimal_char;
		$this->thousand_char = static::$static_thousand_char;
	}
	
	public function setWaitingText($txt = "Loading, please wait" ) {
		$this->waitingText = $txt;
	}
	
	/**
	 * Set a page to load data rather than using the direct 
	 * display.
	 * 
	 * @param string $url the URL for data
	 */
	public function setAjaxPage( $url ){
		$this->ajax = $url;
		$this->opt['ajax'] = $url;
	}

	public function getJavaScript(){
		$ret = "<script>\n";
		$ret .= '$(document).ready( function() {' . "\n";
		$ret .= '   $(\'#' . $this->id . '\').dataTable(' . "\n";
		$json = json_encode( $this->opt );
        foreach( $this->functions as $key => $value ){
           $json = "   " . str_replace( "\"##$key##\"", $value, $json ) . "\n"; 
        }
		$ret .= $json . ");\n";
		
		if( $this->waitingText ){
			$ret .= '$("#d0' . $this->id . "\").hide();\n";
			$ret .= '$("#d1' . $this->id . "\").show();\n";
		}
		
		$ret .= "} );\n";
		// $ret .= '$(\'#' . $this->id . '\').removeClass( \'display\' ).addClass(\'table table-striped table-bordered\');';
		$ret .= "</script>\n";
		return $ret;
	}

	/**
	 * Retrieves an array with no data inside. The
	 * keys are part of the column names given at
	 *	the ::setColumns() call.
	 *
	 */
	public function getEmptyRow(){
		$ret = array();
		foreach( $this->headers as $k=>$v ){
			$ret[$k] = "";
		}
		return $ret;
	}
	
	/**
	 * Hack to add a Javascript function into the JSON
	 */
	private function jsFunction( $fn ){
		$k = uniqid();
		$this->functions[$k] = $fn;
		return "##$k##";
	}
	
	public function setColumns( $columns ){
		$this->headers = array();
		$target = 0;
		$this->columns = array();
		
		foreach( $columns as $colname => $value ){
			$column = array();
			if( $this->ajax ){
				$column['data'] = $colname;
			}
			$column['type'] = "html"; 
			$className = '';
			$values = explode( $this->separator, $value );
			$i = 0;
			foreach( $values as $v ){
				$key = trim($v);

				if( $i == 0) {
					// The name of the column to display
					$this->headers[$colname] = $v;
				}
				else {
                    // Try to have a key/value pair (for width
                    // and other stuff)
					$pos = strpos( $v, "=" );
					if( $pos > 0 ){ 
						$val = trim(substr( $v, $pos + 1 ));
						$key = trim(substr( $v, 0, $pos ));
					}
					else {
						$val = $key;
						$val = null;
					}

					if( $key == 'left' ){
                        // Left-aligned
						$className .= " text-left"; 
					}
					else if( $key == 'right' ){
                        // Right-aligned
						$className .= " text-right";
					}
					else if( $key == 'center' ){
                        // Center aligned
						$className .= " text-center"; 
					}
					else if( $key == 'class' ){
                        // Set a class
						$className = $val;
					}
					else if( $key == 'hidden' ){
                        // Make the column invisible
						$column['visible'] = false;
					}
					else if( $key == 'desc' ){
                        // Sort direction on reverse order
						$column['orderSequence'] = 'desc';
					}
					else if( $key == 'sort' ){
                        // Sort direction
						$column['orderSequence'] = $value;
					}
					else if( $key == 'mailto' ){
                        // Render a mail link
                        $fnRender = "function(val,type) {
                        		var ret = val;
                        		if(type=='display'){
                     		        val = val + \"|\" + val;
                     		        var parts = val.split('|');
                         			ret = '<a href=\"mailto:' + parts[0] + '\">' + parts[1] + '</a>';
                        		}
                                return ret; }";
						$column['render'] = $this->jsFunction($fnRender);
					}
					else if( $key == 'link' ){
                        // Render a link, the value of the
                        // column is divided in 2 parts: the first
                        // is the rendered, the second the reference for the
                        // link.
                        $fnRender = "function(val,type) {
                        		var ret = val;
                        		if(type=='display'){
                        			var re = /(.*)\|([a-zA-Z0-9_\-]+)/
    								var parts = re.exec(val)
    								if (parts) {
    								   	var url = \"$val\";
                                    	var href = url.replace(\"*\", parts[2] );
                                    	var ret = '<a href=\"' + href + '\">' + parts[1] + '</a>';
                                    }
                                    else ret = val;
                                 }
                                 return ret; }";
						$column['render'] = $this->jsFunction($fnRender);
					}
					else if( $key == 'nosort' ){
                        // Remove the column sort
						$column['orderable'] = false;
					}
					else if( $key == 'default' ){
                        // Set a default value
						$column['defaultContent'] = $val;
					}
					else if( $key == 'width' ){
						$column['width'] = $val; 
					}
					else if( $key == 'money'){
						$className .= " align-right"; 
						$column['type'] = "num";
						//$value = ($val ? $val : $this->money_format );
 						$fnRender = "function(val,type) {
 						var ret = val;
 						if(type=='display'){
 							if( val ){
 								nStr = parseFloat(val).toFixed(2);
 								x = nStr.split('.');
 								x1 = x[0];
 								x2 = x.length > 1 ? '" . $this->decimal_char . "' + x[1] : '';";
						if( $this->thousand_char ){
							$fnRender .= "var rgx = /(\d+)(\d{3})/;
							while (rgx.test(x1)) {
								x1 = x1.replace(rgx, '$1' + \"" . $this->thousand_char ."\" + '$2');
							}";
						}
						$fnRender .= "	return x1 + x2;
							}
						}
						return ret; }";
						$column['render'] = $this->jsFunction($fnRender);
					}
					else if( $key == 'date' ){
						// Date formating
						// Use UI Datepicker provided by JQuery UI
						// (make sense as no function is available in Javascript!!!) 
						$value = ($val ? $val : $this->date_format );
						$column['type'] = "date";
						$mRender = "function(val, type) {
										var d = new Date(val * 1000);
										if(type=='display'){
											var ret = \$.datepicker.formatDate( \"$value\", d );
											return ret; 
										}
										return d;
									}";
						$column['render'] = $this->jsFunction($mRender);
					}
					else if( $key == 'numeric' ){
                        // Special for numeric
						$className .= " text-right"; 
						$column['type'] = "num";
					}
					else if( $key == 'optional' ){
						// We hide the column when the screen is very small.
						$className .= " hidden-xs";
					}
					else if( strlen($key) == 0){
						// Simply ignore (usually a ";" added at the end
					}
					
					else {
						throw new Exception( "Unknown qualifier '$key' for column $colname.");
					}
				}
				$i++;
			}
			if( $className ){
				$column['className'] = trim($className);
			}
			$this->columns[$colname] = $column;
		}
		$this->opt['columns'] = array_values($this->columns);
	}
	
	/**
	 * Convert a DateTime object to a Javascript
	 * compatible format to be used in DataTables.
	 */
	public function jsDate( $dt ) {
		return $dt->format("Y-m-d");// . 'T' . $dt->format("H:i:s");
		//return $dt->getTimestamp() * 1000;
	}
	
	public function getHeader( $withJavaScript = TRUE ) {
		$ret = "";
		if( $withJavaScript ) $ret .= $this->getJavaScript();
		$options1 = array( "class"=>"dbtable", 'id'=> 'd1'.$this->id);

		if( $this->waitingText ){
			$ret .= std::tag("div", array( 'id'=>'d0'.$this->id, 'style'=>'display: block;' ));
			$ret .= $this->waitingText;
			$ret .= '</div>';
			$options1['style'] = 'display: none;';
		}

		// $ret .= std::tag("div", $options1 );
		$ret .= std::tagln("table", array('id'=>$this->id, "class"=>"table table-striped table-bordered", "width"=>"100%" )) ;
		$ret .= "<thead>\n <tr>\n";
		foreach( $this->headers as $key => $value ){
		    $ret .= sprintf( "  <th>%s</th>\n", $value );
		}
		$ret .= " </tr>\n</thead>\n";
		$ret .= "<tbody>\n";
		return $ret;
	}

	/**
	 * Write the complete set of data providing
	 * the dataset made of an array having each element
	 * a row which is basically an array of each
	 * column in HTML mode.
	 *
	 * NOTE: this method writes directly to the output
	 * instead of returning the data generated.
	 *
	 */	
	public function writeData( $data ) {
		echo $this->getHeader();
        foreach( $data as $k => $value ) {
    		echo $this->getRow( $value );
    	}
    	echo $this->getFooter();
	}
	
	public function getFooter( ) {
        $ret = "";
		$ret .= "</tbody>\n" . "</table>\n";
        return $ret;
	}

	/**
	 * 
	 * @param unknown_type $data an array containing
	 * 		the expected data to be displayed (in the HTML
	 * 		format). If this variable is not an array, the row
	 * 		is simply ignored.
	 *
	 * @return the row.
	 */
	public function getRow( $data ) {
		$ret = '';
		if( is_array($data) ){
			    $options = array();
			    $rowstyle = '';
			    if( isset($data['tr-style']) ){
				    $rowstyle = $data["tr-style"];
			    }
	    	    if( strlen( $rowstyle ) > 0 ){
	    		    $options["style"] = $rowstyle;
	    	    }
	    	
			    $ret .= std::tagln("tr", $options);
	            foreach( $this->headers as $key => $value ){
	        	    $opt = array();

 	        	    $val = @$data[$key];
	        	    $style = '';
				    if( isset($data["{$key}-style"]) ) $style = $data["$key-style"];
				    if( isset($data["{$key}-order"]) ) $opt['data-order'] = $data["{$key}-order"];
	        	    if( $style || $rowstyle ){
	        		    $opt["style"] = $style . $rowstyle;
	        	    }
				    $ret .= "  " . std::tag("td", $opt ) . $val . std::tagln("/td");
			    }
			    $ret .= std::tagln("/tr");
		}
		return $ret;
	}
	
}

