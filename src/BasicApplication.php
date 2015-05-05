<?php 

namespace Concerto;

/**
 * A very basic application.
 * 
 * To create a PHP application running out-of the box, use the following code:
 * 
 * <pre>
 *   &lt;php
 *     include __DIR__ . '/vendor/autoload.php";
 *     $myApp = new \Concerto\BasicApplication();
 *     echo $myApp->header();
 *     echo $myApp->footer();
 * </pre>
 * 
 * @author wrey75@gmail.com
 *
 */
class BasicApplication {
	
	public $tabs = 0;
	protected $debug = TRUE;
	protected $div = array();
	public $config = array();
	
	public function __construct( $config = array() ){
		if( $config ){
			if( is_string($config) ){
				$config = json_decode($config, TRUE);
			}
			$this->config = $config;
		}
		
		// Initialisation stuff..
		$this->debug = @$this->config["app"]["debug"];
		$this->init();
	}
	
	/**
	 * Initialization called at construct time. Dhould
	 * be overridden by thz implementation.
	 */
	public function init(){
	
	}
	
	/**
	 * Returns tabulations. This function returns spaces
	 * only in debug mode.
	 * 
	 */
	protected function getTabulation() {
		$ret = '';
		if( $this->debug ){
			for( $i = 0; $i < $this->tabs; $i++ ){
				$ret .= '  ';
			}
		}
		return $ret;
	}
	
	/**
	 * Open a new &lt;div&gt; tag. Mainly used to
	 * be used whith the close_div().
	 * 
	 * @param string|array $class a class or a list of classes.
	 * @param string $id a option id for this &lt;div&gt;.
	 */
	public function open_div( $class, $id = null, $attrs = array() ){
		$this->tabs++;
		$attrs['class'] = std::implode($class);
		if( $id ){
			$attrs['id'] = $id;
		}
		array_push( $this->div, [ $id, $class ] );
		return $this->getTabulation() . std::tagln( "div", $attrs );
	}
	
	/**
	 * Closes one or more &lt;div&t; elements.
	 * 
	 * @param number $nb the numver of &lt;div&t; to close (defaults to 1).
	 * @return string the HTML code to display.
	 */
	public function close_div($nb = 1){
		$ret = "";
		while( $nb > 0 ){
			$ret = $this->getTabulation() . "</div>";
			if( $this->debug ){
				// Display the class or ID linked.
				list( $id, $class ) = array_pop( $this->div );
				if( $id ){
					$ret .= $this->comment("#{$id}");
				}
				else {
					if( is_array($class) ) {
						$class = implode(' .', $class);
					}
					else {
						$class = ".{$class}";
					}
					$ret .= $this->comment($class);
				}
				$ret .= "\n";
			}
			$nb--;
			$this->tabs--;
		}
		return $ret;
	}
	
	/**
	 * Returns a CSS link.
	 * 
	 * @param array|string $url an URL or a set of URLs.
	 * @return the HTML tags to load the CSS.
	 * 
	 */
	public function css( $url ){
		if( is_array($url)){
			$ret = "";
			foreach ($url as $u) $ret .= $this->css($u);
			return $ret;
		}
		return $this->getTabulation() . std::tagln("link", ["rel"=>"stylesheet", 'href'=>$url ] );
	}
	
	/**
	 * Generate an HTML5 comment.
	 * 
	 * @param string|array $cmt the comment or several comments in an array.
	 * @return string the HTML code to display (empty if production code).
	 */
	public function comment( $cmt ) {
		$ret = "";
		if( $this->debug ){
			$ret .= "<!-- ";
			if( is_array($cmt) ){
				$ret .= implode("\n" + $this->getTabulation(), $cmt);
			}
			else {
				$ret .= $cmt;
			}
			$ret .= " -->\n";
		}
		return $ret;
	}
	
	public function meta($key, $value){
		return $this->getTabulation() . std::tagln( "meta", [ 'name'=>$key, 'content'=>"width=device-width, initial-scale=1" ]);
	}

	/**
	 * Returns a SCRIPT link.
	 *
	 * @param array|string $url an URL or a set of URLs to get the Javascript files.
	 * @param string $type the type of the script (usually "text/javascript" or simply omitted).
	 * @return the HTML tags to load the script.
	 *
	 */
	public function script( $url, $type = null ){
		if( is_array($url)){
			$ret = "";
			foreach ($url as $u) $ret .= $this->script($u, $type);
			return $ret;
		}
		
		$arr = ['src'=>$url];
		if( $type ) $arr['type'] = $type;
		return $this->getTabulation() . std::tag("script", $arr ) . "</script>\n";
	}
	
	/**
	 * This method will send HTTP headers for compressing the
	 * output and removing IE compatibility mode (for Internet Explorer).
	 * 
	 */
	public function pre_headers( ){
		header("Content-Type: text/html; charset=UTF-8");
		header('x-ua-compatible: ie=edge'); // for IE browsers.
		ini_set( "zlib.output_compression", TRUE );
	}
	
	/**
	 * The HTML5 header. Creates the page (including the UTF-8 output
	 * and set the page gzipped when possible).
	 * 
	 * @param string $title the page title (must be plain text).
	 * @param array $extras some extras for the page. Defaulted to an empty
	 * 		array.
	 * 
	 */
	public function header( $title, $extras = array() ){
		$this->pre_headers();
		
		$ret = "<!DOCTYPE html>\n"
			. "<html>\n"
			. " <head>\n"
			. "  <title>" . std::html($title) . "</title>\n";

		$ret .= "  " . $this->css("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css");
		$ret .= "  " . $this->css("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css");
		$ret .= "  " . $this->script("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js");
		$ret .= "  " . $this->meta("viewport", "width=device-width, initial-scale=1");
		
		// IE8 support
		$ret .= '
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->' . "\n";
  				
		
		$ret .= ""
			. " </head>\n"
			. " <body>\n";
		return $ret;
	}
	
	/**
	 * Generate the footer of the page.
	 * 
	 * @return string the footer to print.
	 */
	public function footer(){		
		return " </body>\n"
			. "</html>\n";
	}
}