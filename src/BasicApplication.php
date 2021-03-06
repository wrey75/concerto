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
	public $IE_version = 0;	// Version of Internet Explorer
	public $bot = FALSE;
	public $log;
	
	public function __construct( $config = array() ){
		if( $config ){
			if( is_string($config) ){
				$config = json_decode($config, TRUE);
			}
			$this->config = $config;
		}
		
		// Set a log file (when possible)
		$log_infos = $this->getConfig("app.log");
		if( $log_infos ){
// 			$filepath = @$log_infos['file'];
// 			$level = @$log_infos['level'];
// 			if( !$level ) $level = Logger::ERROR; 
			$this->log = new Logger($log_infos);
		}
		else {
			$this->log = new Logger("stdout:", Logger::CRITICAL);
		}
		
		// Initialisation stuff..
		$this->debug = $this->getConfig("app.debug", FALSE);
		$this->init();
	}
	
	/**
	 * Returns TRUE if this is the development environment.
	 */
	public function isDebug(){
		return $this->debug;
	}
	
	/**
	 * 
	 * 
	 * @deprecated use getConfig() instead.
	 */
	public function config( $key, $defaultValue = null ){
		return $this->getConfig( $key, $defaultValue );
	}
	
	/**
	 * Get a configuration value.
	 * 
	 * @param string $key a key giving the final key. Use
	 * 		dots to get internal value of an array.
	 * @param $defaultValue a default value (set to NULL
	 * 		by default).
	 * @return Ambigous <string, multitype:, NULL> the
	 * 		value from the configuration.
	 */
	public function getConfig( $key, $defaultValue = null ){
		// Check with hierarchy.
		$keys = explode('.', $key);
		$arr = $this->config;
		foreach ($keys as $k){
			$arr = isset( $arr[$k] ) ? $arr[$k] : NULL;
		}
		return ( $arr !== NULL ? $arr : $defaultValue );
	}
	
	/**
	 * Initialization called at construct time. Should
	 * be overridden by thz implementation.
	 */
	public function init(){
	
	}
	
	/**
	 *  Returns the Microsoft Internet Explorer version. If the
	 *  user agent is not a IE browser, the returned value is 100.
	 * 
	 *  @return the IE version or 100 if not Microsoft.
	 */
	public function IE_version() {
		if( !$this->IE_version ){
			if( preg_match( '/MSIE ([0-9.]*)/', @$_SERVER['HTTP_USER_AGENT'], $matches ) ){
				$this->IE_version = floatval( $matches[1] );
			}
			else {
				$this->IE_version = 100;
			}
		}
		return $this->IE_version;
	}
	
	/**
	 * Show the sub menu. The $submenu passed as parameter
	 * is an associative array containing the URLs as keys
	 * and the text as values.
	 * 
	 * @param array $submenu the sub menu to display.
	 */
	protected function show_sub_menu( $submenu ){
		$ret = $this->tabbed( '<ul class="dropdown-menu" role="menu">' );
		$this->tabs++;
		foreach( $submenu as $k => $v ){
			// Only 2 levels accepted...
			$ret .= $this->add_menu_item( ['url'=>$k, 'text'=>$v /* std::html($v)*/ ] );
		}
		$this->tabs--;
		$ret .= $this->tabbed('</ul>');
		return $ret;
	}
	
	/**
	 * Add a menu item.
	 * 
	 * @param array $item an associative array containing the URL as
	 * 		'url' key and the text to display (in HTML) as the
	 * 		'text' key. The key 'active' can be set to true.
	 * @return a &lt;li&gt; tag containing the link.
	 */
	protected function add_menu_item( $item ){
		$attribs = array();
		if( isset($item['active']) ){
			$attribs['class'] = 'active';
		}
		$url = $item['url'];
		$text = $item['text'];
		if( $text ){
			$ret = std::tag("li", $attribs) . std::link($url, $text ) . '</li>';
		}
		else {
			$ret = '<li role="separator" class="divider"></li>';
		}
		return $this->tabbed($ret);
	}
	
	/**
	 * Show the navigation bar menu based on the menu
	 * passed as the parameter. 
	 * 
	 * @param array $menu the menu for the application
	 * @return string the menu as it must be displayed.
	 */
	public function show_menu( $menu, $active = null ) {
		$ret = $this->tabbed( '<ul class="nav navbar-nav">' );
		foreach( $menu as $k => $v ){
			if( is_array($v) ){
				// Sub menu
				$ret .= $this->tabbed( '<li class="dropdown">' );
				$this->tabs++;
				$ret .= $this->tabbed( '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $k . '<span class="caret"></span></a>' );
				$ret .= $this->show_sub_menu( $v );
				$this->tabs--;
				$ret .= $this->tabbed( '</li>');
			}
			else {
				$ret .= $this->add_menu_item( ['url'=>$k, 'text'=>$v /*std::html($v)*/] );
			}
		}
		$ret .= $this->tabbed( '</ul>' );
		return $ret;
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
	public function open_div( $class = null, $id = null, $attrs = array() ){	
		if( $class ) $attrs['class'] = std::implode($class);
		if( !$id && !$class ) $id = uniqid();
		if( $id ){
			$attrs['id'] = $id;
		}
		array_push( $this->div, [ $id, $class ] );
		$ret = $this->getTabulation() . std::tagln( "div", $attrs );
		$this->tabs++;
		return $ret;
	}
	
	/**
	 * Open a tag in the application. This method manages
	 * the tabulations. Then, you should always close the tag
	 * somewhere. It is a convenient way to have well displayed
	 * tags in debug mode.
	 *
	 * @param string $tag
	 * @param array $attribs the attributes for the tag.
	 */
	public function open_tag( $tag, $attribs = [] )
	{
		$ret = $this->getTabulation() . std::tagln( $tag, $attribs );
		$this->tabs++;
		return $ret;
	}
	
	/**
	 * Closes the previous opened tag. The tabulation is then reset
	 * of one. NOTE: we assume you close the correct tag (there is
	 * no control).
	 *
	 * @param string $tag the tag (without the "/" character).
	 */
	public function close_tag( $tag )
	{
		$this->tabs--;
		$ret = $this->getTabulation() . "</$tag>\n";
		return $ret;
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
			$this->tabs--;
			$ret .= $this->getTabulation() . "</div>";
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
			if( is_array($cmt) ){
				$ret = "<!--" . implode("\n" + $this->getTabulation(), $cmt) . "\n-->";
			}
			else {
				$ret = "<!-- {$cmt} -->";
			}
		}
		return $ret;
	}
	
	/**
	 * Returns the HTML provided as parameter with spaces
	 * and a return character.
	 * 
	 * @param string $html original HTML.
	 * @return string formatted HTML.
	 */
	protected function tabbed( $html ){
		return $this->getTabulation() . "$html\n";
	}
	
	/**
	 * Create a &lt;META&gt; tag.
	 * 
	 * @param string $key the data for the "name" attribute. 
	 * @param string $value the data for the "content" attribute. 
	 */
	public function meta($key, $value){
		return $this->getTabulation() . std::tagln( "meta", [ 'name'=>$key, 'content'=>$value ]);
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

		
		$ret .= "  " . $this->css("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css");
		$ret .= "  " . $this->css("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css");
		$ret .= "  " . $this->script("https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js");
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
	
	/**
	 * Display an header.
	 * 
	 * @param int $level the level (from 1 to 6)
	 * @param string $text the text in plain UTF-8.
	 * @param string $subtext a sub text (not mandatory)
	 * @return string the HTML to display.
	 */
	public function h( $level, $text, $subtext = null ){
		$ret = sprintf( "<h%d>", $level );
		$ret .= std::html($text);
		if( $subtext ){
			$ret .= " <small>" . std::html($subtext) . "</small>";
		}
		$ret .= sprintf( "</h%d>", $level );
		return $this->getTabulation() . $ret . "\n";
	}
	
	public function h1( $title, $subtitle = null ){
		$ret = $this->open_div("page-header");
		$ret .= $this->getTabulation() . $this->h(1, $title, $subtitle);
		$ret .= $this->close_div();
		return $ret;
	}
	
	
	/**
	 * Formatte the text into a paragraph.
	 * 
	 * @param string $html the text of the paragraph
	 * 		previously formatted in HTML.
	 * @return string the HTML embedded with &lt;P&gt; and &lt;/P&gt;.
	 */
	public function p( $html, $attr = array() ){
		if( !is_array($attr) ){
			// If the attr is not an array, we consider as a class.
			$cls = $attr;
			$attr = array( "class" => $cls );
		}
		$ret = std::tag("p", $attr) . $html . "</p>\n";
		return $this->getTabulation() . $ret;
	}
	
	
	public function debug($msg){
		$this->log->debug($msg);
	}

	public function info($msg){
		$this->log->info($msg);
	}
	
	public function fatal($msg){
		$this->log->fatal($msg);
	}
	
	public function warn($msg){
		$this->log->warn($msg);
	}
	
	public function error($msg){
		$this->log->error($msg);
	}
	
	
	/**
	 * Convert a plain text to an HTML
	 * text including french quotes.
	 * 
	 * @param string $texte plain text
	 */
	public function frquotes( $texte )
	{
		return "&laquo;&nbsp;" . std::html($texte) . "&nbsp;&raquo;";
	}
}