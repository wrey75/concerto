<?php 

namespace Concerto\Form;

use Concerto\std as std;


/**
 * This class helps to create forms using Bootstrap CSS style.
 * It is not awesome but easy to use.
 * 
 * You can create HORIZONTAL or classic forms.
 * 
 * I added some stuff for generating AngularJS.
 * 
 * @author wrey75@gmail.com
 *
 */
class BootstrapForm {
	
	const HORIZONTAL = 'form-horizontal';
	
	public $horiz_columns;
	public $mode;
	public $group;
	public $hintGroup;
	public $forId;
	public $horiz_label_class;
	public $horiz_field_class;
	public $default_data;
	public $method;
	public $action;
	public $toggle_buttons;
	public $scripting = '';
	public $encasulate_enabled;
	public $error_list;
	public $input_size;
	public $required = false;
	public $disabled = false;
	
	public $hint_class; // Class(es) for hints.
	
	// Angular
	protected $angularCtrl = FALSE; // Set to true to use angular (experimental)
	protected $angularModel = '';
	
	public function __construct( $base_object = array() ) {
		$this->group = null;
		$this->mode = self::HORIZONTAL;
		$this->set_field_columns( 2 );
		$this->default_data = (array)$base_object;
		$this->method = 'POST';
		$this->action = $_SERVER["SCRIPT_NAME"];
		$this->tooltips = false;
		$this->toggle_buttons = false;
		$this->scripting = '';
		$this->encasulate_enabled = true;
		$this->error_list = array();
		$this->input_size = '';
		$this->angularCtrl = FALSE;
		
		$this->hint_class = 'help-block hidden-xs';
	}
	
	/**
	 * Set the next fields in the group to be required.
	 * Note this attribute is only valid for input tags
	 * (not necessarly all the tags available in a form).
	 * 
	 * The library uses the internal HTML attribute (then
	 * this method is not fully reliable).
	 * 
	 * @param $required if the next field is required.
	 * 
	 * @return an empty string.
	 */
	public function setRequired($required = true){
		$this->required = $required;
		return '';
	}
	
	/**
	 * Set the next fields in the group to be disabled.
	 * Note this attribute is only valid for input tags
	 * (not necessarly all the tags available in a form).
	 *
	 * The library uses the internal HTML attribute (then
	 * this method is not fully reliable).
	 *
	 * @param boolean $disabled if the next field is required.
	 *
	 * @return an empty string.
	 */
	public function setDisabled($disabled = true){
		$this->disabled = $disabled;
		return '';
	}
	
	/**
	 * Set the form to be angular compatible. This method
	 * should be called as soon as possible after the creation of
	 * the form, if you call it after having created field, the behaviour
	 * is unknown.
	 * 
	 * The name passed is the controller name for the form.
	 * 
	 * @param boolean|string $angular 
	 * 		TRUE if the form is driven by Angular. Better to pass
	 * 		a string variable which is the name of the controller
	 * 		("ng-controller") for the controller.
	 * 
	 */
	public function setAngularController( $controllerName, $model = null ){
		$this->angularCtrl = $controllerName;
		$this->angularModel = $model;
	}
	
	/**
	 * Used to help when a model is provided.
	 * 
	 * @param string $name the name of the variable.
	 */
	protected function angular_model( $name ){
		$ret = '';
		if( $this->angularModel ) $ret .= $this->angularModel . '.';
		return $ret . $name;
	}
	
	/**
	 * Modify the number of columns allocated in the
	 * case of a form in the horizontal format.
	 * 
	 */
	protected function set_field_columns( $cols, $type = 'sm' ){
		if( !$cols ) {
			$this->horiz_label_class = '';
			$this->horiz_field_class = '';
		}
		else {
			$this->horiz_label_class = "col-{$type}-{$cols}";
			$this->horiz_field_class = "col-{$type}-" . (12 - $cols);
		}
	}
	
	/**
	 * The opening tag.
	 * 
	 * @return string the &lt;form&gt; tag according to the 
	 * 		information provided.
	 */
	public function open(){
		$arr = array('class'=>$this->mode, 'role'=>'form' );
		if( $this->angularCtrl ){
			if( is_string($this->angularCtrl) ){
				$arr['ng-controller'] = $this->angularCtrl;
			}
			$arr['ng-submit'] = $this->action;
		}
		else {
			$arr['action'] = $this->action;
			$arr['method'] = $this->method;
		}
		$ret = std::tagln('form', $arr);
		return $ret;
	}
	
	
	/**
	 * The closing tag.
	 * 
	 * @return string the string the display.
	 */
	public function close() {
		$ret = "\n";
		if( $this->toggle_buttons ){
			
			if( $this->toggle_buttons ){
				$this->scripting .= "
  	$('.btn-toggle').click(function() {
  	    $(this).find('.btn').toggleClass('active');  
  	    
  	    if ($(this).find('.btn-primary').size()>0) {
  	    	$(this).find('.btn').toggleClass('btn-primary');
  	    }
  	    if ($(this).find('.btn-danger').size()>0) {
  	    	$(this).find('.btn').toggleClass('btn-danger');
  	    }
  	    if ($(this).find('.btn-success').size()>0) {
  	    	$(this).find('.btn').toggleClass('btn-success');
  	    }
  	    if ($(this).find('.btn-info').size()>0) {
  	    	$(this).find('.btn').toggleClass('btn-info');
  	    }
  	    
  	    $(this).find('.btn').toggleClass('btn-default');
  	       
  	});\n";
				
			}
			
		}
		
		if( $this->scripting ){
			// Add the libraries...
			//  $ret .= std::tag("script", array('src'=>$app->res( '/libraries/typeahead/0.10.5/bloodhound.min.js'))) . "</script>\n";
			$ret .= "\n<script>\n";
			$ret .= "$(function() {\n";
			$ret .= $this->scripting;
			$ret .= "});\n";
			$ret .= "\n</script>\n";
		}
		
		$ret .= "</form>\n";
		if( $this->group ) $ret = $this->close_group() . $ret;
		if( $this->tooltips ){
			$ret .= "<script>\n\$(function () { $(\"[data-toggle='tooltip']\").tooltip(); });\n</script>\n	";
		}
		return $ret;		
	}
	
	/**
	 * Close the group. This method is called implitcitly
	 * when closing the form or creating a new group.
	 * 
	 * @return string the output.
	 */
	public function close_group() {
		$this->group = null;
		$this->required = false;
		$this->disabled = false;
		return "</div>\n";
	}
	
	protected function close_group_if_necessary() {
		$ret = "";
		if( $this->group ){
			$ret .= $this->close_group();
		}
		return $ret;
	}
	
	/**
	 * Create a new group.
	 * @param string $name the name of the group.
	 * 
	 * @return string the HTML text to display. 
	 */
	public function new_group( $name, $hint_text = '' ) {
		$ret = $this->close_group_if_necessary(); 
		$this->group = $name;
		$this->hintGroup = $hint_text;
		if( $this->hintGroup ){
			$this->tooltips = true;
		}
		$classes = "form-group";
		if( isset($this->error_list[$name]) ){
			$classes .= ' has-error';
		}
		$ret .= std::tagln( 'div', array( 'class'=>$classes, 'id'=>"group_{$name}"));
		return $ret;
	}


	/**
	 * Generate a label.
	 * 
	 * @param string $text the HTML text to show.
	 * @param string $forId the "id" for the input tag.
	 * 		Should be passed null as the group name is
	 * 		used instead.
	 * @return string the output.
	 */
	public function label( $text, $forId = null ) {
		if( !$forId ) $forId = $this->group;
		$arr = array('for'=>$forId);
		if( $this->mode == self::HORIZONTAL ){
			$arr['class'] = "{$this->horiz_label_class} control-label";
		}
		$ret = std::tag('label', $arr) . $text . "</label>\n";
		$this->forId = $forId;
		return $ret;
	}
	
	/**
	 * Try to resolve the value for the field. By default,
	 * the function tries to find a compatible value in the
	 * parameters' request. If not, use the default object
	 * passed when the form has been constructed.
	 * 
	 * @param string $name the name of the field.
	 * @return mixed the gussed value for the field.
	 */
	public function find($name, $default_val = ''){
		if( $this->default_data ){
			if( isset($this->default_data[$name]) ){
				$default_val = @$this->default_data[$name];
			}
		}
		$value = std::get( $name, $default_val );
		return $value;
	}
	
	/**
	 * The hidden value.
	 * 
	 * @param string $name the name of the data.
	 * @param unknown $value the value of the data (mandatory).
	 * @return string the outoput.
	 */
	public function hidden( $name, $value = null ) {
		if( $value === null ){
			if( $this->default_data ){
				$value = @$this->default_data[$name];
			}
			else {
				$value = '';
			}
		}
		if( $this->angularCtrl ){
			$ret = std::tagln('input', array( 'type'=>'hidden', 'model'=>$this->angular_model($name) ));
		}
		else {
			$ret = std::tagln('input', array( 'type'=>'hidden', 'name'=>$name, 'value'=>$value ));
		}
		return $ret;
	}
	
	public function horizontal($ret){
		if( $this->mode == self::HORIZONTAL && $this->encasulate_enabled ){
			$ret = std::tagln('div', array('class'=>$this->horiz_field_class)) . $ret . "</div>\n";
		}
		return $ret;
	}
	
	public function toogle_inline_buttons( $name, $values ){
		$this->toggle_buttons = true;
		$default_val = $this->find($name);
		$ret = '<div class="btn-group btn-toggle" data-toggle="buttons">' . "\n";
		foreach( $values as $k=>$v ){
			$classes = "btn";
			$arr = array("type"=>"radio", "name"=>$name, "id"=>"{$name}_{$k}", "value"=>$k);
			if( $k == $default_val ){
				$arr[] = "checked";
				$classes .= " btn-primary active";
			}
			else {
				$classes .= " btn-default";
			}
			$ret .= std::tagln("label", array("class"=>$classes));
			$ret .= std::tag("input", $arr);
			$ret .= $v;
			$ret .= "</label>\n";
		}
		$ret .= "</div>\n";
		
		// TODO: fix the bug because nothing shown in editing a record.
		$ret = $this->horizontal($ret);
		return $ret;
	}
	
	public function radio_inline_buttons($name, $values) {
		$ret = "";
		$default_val = $this->find($name);
		foreach( $values as $k=>$v ){
			$ret .= std::tagln("label", array("class"=>"radio-inline"));
			$arr = array("type"=>"radio", "name"=>$name, "id"=>"{$name}_{$k}", "value"=>$k);
			if( $k == $default_val ){
				$arr[] = "checked";
			}
			$ret .= std::tag("input", $arr);
			$ret .= $v;
			$ret .= "</label>";
		}
		$ret = $this->horizontal($ret);
		return $ret;
	}
	
	protected function hint_text(){
		$ret = '';
		if( $this->hintGroup ){
			//  			// Rewrite the tag.
			//  			$attributes['title'] = $this->hintGroup;
			//  			$attributes['data-toggle'] = 'tooltip';
			// 			$ret = std::tag('a', array('href'=>"#", 'data-toggle'=>"tooltip", 'title'=>$this->hintGroup)) . $ret . '</a>';
			$span = std::tag('span', [ 'class'=>$this->hint_class ] );
			$ret .= $span . $this->hintGroup . "</span>\n";
		}
		return $ret;
	}

	public function input_number( $name, $min = null, $max = null, $step = null ) {
		$attributes = array(
				'name' => $name,
				'type' => 'number',
				'class' => "form-control {$this->input_size}",
				'size' =>10,
				'value' => $this->find($name) );
		
		if( $this->forId ){
			$attributes['id'] = $this->forId;
		}
		else {
			$attributes['id'] = uniqid('input');
		}
		if( is_numeric($min) ){
			$attributes['min'] = $min;
		}
		if( is_numeric($max) ){
			$attributes['max'] = $max;
		}
		if( is_numeric($step) ){
			$attributes['step'] = $step;
		}	
		$ret = std::tag('input', $attributes) . "\n";
		$ret .= $this->hint_text();
		$ret = $this->horizontal($ret);
		return $ret;
	}
	
	public function textarea( $name, $placeholder = null, $rows = null ) {
		$attributes = array(
				'class' => "form-control {$this->input_size}" );
		
		// Add the model.
		if( $this->angularCtrl ){
			$attributes['ng-model'] = $this->angular_model($name);
		}
		else {
			$attributes['name'] = $name;
		}
		
		// Add the identity if exists.
		if( $this->forId ){
			$attributes['id'] = $this->forId;
		}
		else {
			$attributes['id'] = uniqid('input');
		}
		
		if( $placeholder ){
			$attributes['placeholder'] = $placeholder;
		}
	
		if( $rows ){
			$attributes['rows'] = $rows;
		}
		
		$ret = std::tag('textarea', $attributes);
		if( !$this->angularCtrl ){
			$ret .= std::html($this->find($name));
		}
		$ret .= "</textarea>\n";
		$ret .= $this->hint_text();
		$ret = $this->horizontal($ret);
		return $ret;
	}
	
	
	public function autocomplete( $name, $default_label, $default_id, $search_url ) {
		$val = $this->find($name, $default_id);
		$ret = std::tagln('input', array('type'=>'hidden', 'name'=>$name, 'value'=>$val ));
		
		$name2 = "{$name}_label";
		$ret .= std::tagln( 'input', array('type'=>"text", 
				'class' => "form-control  {$this->input_size}",
				'name'=>$name2,
				'id'=>"{$name}_id",
				'value'=>$this->find($name2, $default_label)));
	
		$this->scripting .= "$( '#{$name}_id' ).autocomplete({
			source: '{$search_url}',
			minLength: 1,
			delay: 500,
			change: function( event, ui ) {
				if( !ui.item ){
					this.value = '';
				}
				else {
					$( 'input[name={$name}]' ).val(ui.item.id);
				}
			} 
		});\n";
		
		$ret .= $this->hint_text();
		$ret = $this->horizontal($ret);
		return $ret;
	}
	
	/**
	 * Create an input entry.
	 * 
	 * @param string $type the type ("text", "email"...)
	 * @param string $name the variable name. 
	 * @param string $placeholder the placeholder if exists.
	 * @return string
	 */
	protected function input( $type, $name, $placeholder = null ) {
		$attributes = array(
				'type' => $type,
				'class' => "form-control {$this->input_size}" );
		if( $this->angularCtrl ){
			$attributes['ng-model'] = $this->angular_model($name);
		}
		else {
			$attributes['value'] = $this->find($name);
			$attributes['name'] = $name;
		}
		
		if( $this->forId ){
			$attributes['id'] = $this->forId;
		}
		else {
			$attributes['id'] = uniqid('input');
		}
		if( $placeholder ){
			$attributes['placeholder'] = $placeholder;
		}
		
		if( $this->disabled ){
			// Add the disable qttribute
			$attributes[] = 'disabled';
		}
		else if( $this->required ){
			// Add the required qttribute
			// Not compatible with DISABLED.
			$attributes[] = 'required';
		}
		
		$ret = std::tag('input', $attributes) . "\n";
		$ret .= $this->hint_text();
		$ret = $this->horizontal($ret);
		return $ret;
	}
	


	/**
	 * Inputs an e-mail. Rely on the browser for inputting an e-mail.
	 * Should be preferred over the basic "text" type to enable
	 * special keys on the mobile (virtual) keyboards.
	 * 
	 * @param string $name name of the variable.
	 * @param string $placeholder the placeholder if exists.
	 */
	public function input_email( $name, $placeholder = null ) {
		return $this->input('email', $name, $placeholder);
	}
	
	/**
	 * Inputs text.
	 * 
	 * @param string $name name of the variable.
	 * @param string $placeholder the placeholder if exists.
	 * @return string the HTML to display.
	 */
	public function input_text( $name, $placeholder = null ) {
		return $this->input('text', $name, $placeholder);
	}
	
	/**
	 * Force a INPUT disabled. You can set the disable flag
	 * to TRUE and use a specific type but if you know the
	 * input is disabled, simply use this method.
	 * 
	 * @param string $name name of the input (should be ignored)
	 * @param string $placeholder placeholder for this input (should be ignored).
	 */
	public function input_disabled( $name, $placeholder = null ) {
		$this->setDisabled();
		return $this->input('text', $name, $placeholder);
	}
	
	
	protected function inject_into_angular($id, $name, $places ){
		$ret = "
		var scope = angular.element(document.getElementById('" . $id ."')).scope();
		scope.\$apply(function() {
			scope.{$this->angular_model($name)} = {$places};
		});
		";
		return $ret;
	}

	/**
	 * Inputs an address. Clearly not included in HTML5,
	 * this input uses the Google Maps API to get a valid
	 * address (with completion).
	 * 
	 * See http://www.findsourcecode.com/google/how-to-make-an-autocomplete-address-field-with-google-map-api/ 
	 * for my implementation reference and some really impressive angular tricks:
	 * http://stackoverflow.com/questions/15424910/angularjs-access-scope-from-outside-js-function
	 * 
	 * NOTE: in Angular, you get _all_ the information (including the address components and
	 * position).
	 * 
	 * 
	 * @param string $name name of the variable.
	 * @param string $placeholder the placeholder if exists.
	 * @return string the HTML to display (including some
	 * 	javascript).
	 *
	 */
	public function input_address( $name ) {
		$id = uniqid('ang');
		
		if( !$this->forId ) {
			// Force an ID.
			$this->forId = uniqid('input');
		}
		
		$attributes = array(
			'type' => 'text',
			'class' => "form-control {$this->input_size}",
			'value' => $this->find($name),
			'name' => $name,
			'id' => $this->forId
		);
		$ret = std::tag('input', $attributes) . "\n";
		$ret .= $this->hint_text();
		$ret = $this->horizontal($ret);
		
		$ret .= "\n" . std::tag( 'script', ['src'=>"https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places" ]) ."</script>\n";
		$ret .= 
		"<script type=\"text/javascript\">
				
		function initialize() {
			var input = document.getElementById('" . $this->forId . "');
			var options = {}; 
			var autocomplete = new google.maps.places.Autocomplete(input, options);
		";
		if( $this->angularCtrl ){
			$ret .= "
				google.maps.event.addListener(autocomplete, 'place_changed', function() {
					" 
					. $this->inject_into_angular($id, $name, 'autocomplete.getPlace()') .
					"
  				});
  			";
		}
		$ret .= "
		}
		google.maps.event.addDomListener(window, 'load', initialize);\n";

		$ret .= "\n</script>\n";
		
		if( $this->angularCtrl ){
			$ret .= std::tag( "input", [ 'type'=>'hidden', 'id'=>$id, 'ng-model'=>$this->angular_model($name)] );
		}
		return $ret;
	}
	
	/**
	 * Second round is the full search box including the map
	 * attached.
	 * 
	 * @param string $name the variable name.
	 * 
	 */
	public function input_search_box( $name, $placeholder = null ) {
		// This example adds a search box to a map, using the Google Place Autocomplete
		// feature. People can enter geographical searches. The search box will return a
		// pick list containing a mix of places and predicted search terms.
		
		// TODO: code refactoring with the input_address()
		$ret = "\n" . std::tag( 'script', ['src'=>"https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places" ]) ."</script>\n";
		
		// TODO: the variables are hard-coded (not posible to use multiple search boxes)
		// TODO: no code for angular.
		$ret .= "
		   <script type=\"text/javascript\">

function initialize() {

  var markers = [];
  var map = new google.maps.Map(document.getElementById('map-canvas'), {
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });

  var defaultBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(44.5, -5),
      new google.maps.LatLng(49.5, +20));
  map.fitBounds(defaultBounds);

  // Create the search box and link it to the UI element.
  var input = /** @type {HTMLInputElement} */(
      document.getElementById('pac-input'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));

  // [START region_getplaces]
  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      var marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);
					" 
					. $this->inject_into_angular('pac-input', $name, 'places') .
					"
      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
  });
  // [END region_getplaces]

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
}

	google.maps.event.addDomListener(window, 'load', initialize);

    </script>
				
	<style>
	.controls {
        margin-top: 16px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }
				
	  #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 400px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }
				
	</style>
";
		$attrs = [ 'id'=>"pac-input", 'class'=>"controls", 'type'=>"text" ];
		if( $placeholder ) $attrs['placeholder'] = $placeholder;
		$ret .= std::tagln('input', $attrs );
		$html = std::tag('div', [ 'id'=>"map-canvas", "style"=>"height: 300px;" ]) . "</div>\n";
    	
		$ret = $ret . $this->horizontal($html);
		return $ret;
	}
	
	/**
	 * A submit button.
	 * 
	 * @param unknown $text the plain text to put in the
	 * 		submit button.
	 * @return the HTML to display.
	 */
	public function submit_button( $text = "Submit" ) {
		return $this->submit_buttons( array('submit'=>$text) );
	}
	
	/**
	 * Show the buttons. The array contains the button name as the
	 * key and the value is displayed to the user. 
	 * 
	 * @param array $arr an associative array of buttons for validation.
	 * 
	 * @return string the HTML to display.
	 */
	public function submit_buttons( $arr ) {
		$ret = $this->close_group_if_necessary();
		
		$btns = "";
		foreach( $arr as $k=>$v ){
			$classes = "btn";
			if( $k == 'submit' ) $classes .= ' btn-primary';
			if( $k == 'success' ) $classes .= ' btn-primary';
			if( $k == 'delete' ) $classes .= ' btn-danger';
			$btns .= std::tagln('input', array('type'=>'submit', 'class'=>$classes, 'name'=>$k, "value"=>$v));
		}
		
		if( $this->mode == self::HORIZONTAL ){
			$ret .= std::tagln("div", [ 'class'=>"form-group" ]);
				
			$ret .= std::tag('div', array('class'=>$this->horiz_label_class)) . "&nbsp;</div>\n";
			$ret .= std::tagln('div', array('class'=>$this->horiz_field_class)) . "{$btns}</div>\n";
			$ret .= "</div>\n";
		}
		else {
			$ret .= $btns;
		}
		return $ret;
	}
	
}
