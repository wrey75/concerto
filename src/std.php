<?php

namespace Concerto;


/**
 * This is a basic class for PHP development. It is
 * intended to implement very useful stuff not available
 * directly in the PHP language.
 * 
 * The public function are "static".
 * 
 * @author wrey75@gmail.com
 *
 */
class std {

	/**
	 * Redirect to a page. Once called, this function do NOT
	 * return. It is a temporary rediction not a permanent one.
	 * To ensure a permanent redirection, you must implement
	 * a variant of this function.
	 * 
	 * @param string $url an URL containing the 
	 * 	page where the user will be redirected. This
	 * 	URL should be complete (including the server).
	 * @param boolean $temporary TRUE if this page must redirect
	 * 		with an HTTP response 302 (FALSE to redirect permanently
	 * 		with code 301).
	 */
	public static function redirect( $url, $temporary = FALSE ){
		header( "Content-type: text/html");
		header( "Location: $url", TRUE, ($temporary ? 302 : 301) );
		echo "Redirected to <a href=\"$url\">$url</a>\n";
		die(0);
	}
	
	
	/**
	 * Converts a filename to its locale name version. 
	 * If you have a file named "myfile.html", you can localize 
	 * it by adding the language, and the country if you want to
	 * be specialized.
	 * 
	 * For example: 
	 * "myfile.fr.html" or "myfile.fr_FR.html" for a country 
	 * language. Note the "_" must be used and country plus language 
	 * is higher priority (of course) than the simple language file.
	 * The returned file is the full name if exists or the original
	 * if no specialized file have been found.
	 * 
	 * @param string $name the name of the file to convert
	 * 		in its locale version.
	 * @param string $lang the language expressed in 2 letters 
	 * 		or 2 letters followed
	 * 		by an underscore and the country (ex: "fr_FR").
	 */
	public static function localeFilename( $name, $lang ){
		if( !$lang ) return $name;
		$lang = str_replace( "-", "_", $lang );
		
		// Try with language and country
		$name1 = preg_replace( "/(.*)[.]([^.]*)/", "\$1.${lang}.\$2", $name );
		if( file_exists($name1) ) return $name1;
		
		// Try with only the language.
		$lang = substr( $lang, 0, 2 );
		$name1 = preg_replace( "/(.*)[.]([^.]*)/", "\$1.${lang}.\$2", $name );
		if( file_exists($name1) ) return $name1;

		// If there is no specialized version
		return $name;
	}
	
	/**
	 * Returns the last char of a string.
	 * 
	 * @param string $str the string.
	 * @return string the last char of the string.
	 */
	public static function lastChar( $str ){
		$len = strlen( $str );
		if( $len > 0 ){
			return substr( $str, $len - 1, 1 );
		}
		return "\0";
	}
	
	/**
	 * Convert a link to a script tag.
	 * 
	 * @param string $src the source.
	 * @return string the tag to display.
	 */
	public static function script( $src, $type = "text/javascript" ) {
	  	$ret = std::tag( 'script', array( 'src'=>$src, 'type'=>$type ) ) . "</script>\n";
	  	return $ret;
	}
  
	/**
	 * This function creates a HTML/XML tag based
	 * on the name and the attributes.
	 * 
	 * If the name finishes with "/", the tag will
	 * be an opening one and a closing one. Typically
	 * the tag named "img/" will produce <img .... />
	 * 
	 * @param string $name
	 * @param array $attributes 
	 */
	public static function tag( $name, $attributes = array() ){
		$close = ">";
		if( $name[ strlen($name)-1 ] == "/" ){
			$name = substr($name, 0, -1);
			$close = "/>";
		}
		$tag = "<" . $name;
  	
		if( count($attributes) > 0 ){
			if( !is_array($attributes) ){
				echo "<pre>"; debug_print_backtrace(); echo "</pre>";
				trigger_error( "array expected but [".$attributes."] received." );
			}
			else {
				foreach( $attributes as $key => $value ){
					if( is_int($key) ){
						// Tag without "=" like "ckecked"
						$tag .= ' ' . $value;
					}
					else if( isset($value) ){
						if( is_array($value) ){
							// When the argument is given as an array, implode it to
							// a single value (usefull for "class" attribute). 
							$value = implode(' ',$value);
						}
						$tag .= ' '.$key.'="'
							.str_replace("\"", "&quot;", str_replace("&", "&amp;", $value))
							.'"';
					}
				}
			}
		}
		$tag .= $close;
		return $tag;
	}
	
	/**
	 * Implode the value passed as parameter.
	 * 
	 * @param unknown $arr a value to implode.
	 * @return string the string value of the imploded value. 
	 */
	public static function implode( $arr ){
		if( $arr === NULL ) return '';
		if( is_array( $arr ) ){
			return implode( ' ', $arr );
		}
		return (string)$arr;
	}

  	/**
  	 *	Used to produce a tag but with a end of line just after.
	 *	This an helper which calls std::tag().
  	 * 
  	 */
	public static function tagln( $tagname, $attributes = array() ){
		return static::tag( $tagname, $attributes ) . "\n";
	}

	/**
	 *	@deprecated used std::link instead.
	 */
	public static function url( $url, $text = null ){
		if( $text === NULL ) $text = $url;
		return std::tag('a', array('href'=>$url)) . $text . '</a>';
	}

	/**
	 *	Create an anchor. 
	 *
	 *	@param string $url the URL to follow
	 *	@param string $text the text to display in the HTML format.
	 *  @param array $attrs the complementary attributes for the
	 *  	anchor tag.
	 *
	 */
	public static function link( $url, $text = null, $attrs = [] ){
		if( $text === NULL ) $text = $url;
		$attrs['href'] = $url;
		return std::tag('a', $attrs) . $text . '</a>';
	}

	/**
	 * Make an URL from the HREF and the parameters passed
	 * in the array. This is useful for creating valid
	 * links even if some stuff has special characters.
	 *
	 * 
	 * @param $href the main URL (encoded in UTF-8).
	 * @param $params parameters for GET.
	 */
	public static function mkurl( $href, $params = array() ){
		$url = ($href ? $href : $_SERVER['PHP_SELF'] );
		$first = true;
		foreach( $params as $key => $value ){
			if( is_array($value) ){
				// In values, you can have one-dimenesion arrays.
				$j = 0;
				foreach( $value as $item ){
					if( $j > 0 ) $var .= "&";
					$var .= $key . "=" . urlencode($item);
					$j++;
				}
  			}
 			else {
				// Normal case
				$var = $key . "=" . urlencode($value);
			}
			$url .= ($first ? "?" : "&") . $var;
			$first = false;
		}
		return $url;
	}

	/**
	 *	When variables made of dots are passed through the
	 *	POST or GET HTTP requests, PHP changes the original
	 *	name by changing the original dot to an underscore.
	 *	This behaviour is not expected in some cases, then the
	 *	method is called to "normalize" the input variables.
	 *	NOTE: not sure only dots are concerned but it is
	 *	sufficient for my personnal code.
	 *
	 */
	static public function normalizeVariableName( $name ){
		$name2 = str_replace( '.', '_', $name );
		return $name2;
	}
    
	/**
	 * Get the variables from the QUERY_SRING value
	 * (through a GET or a POST). Give them back
	 * as a global variable you can access directly
	 * in the script context. The variables must be
	 * comma separated. If the parameter does not
	 * exist, the variable is set to NULL.
	 * Nevertheless, you can give a default value
	 * as the name is followed by a equal sign and the
	 * default value.
	 *
	 *	@deprecated using std::request() is not
	 *	a good pattern as the method injects
	 *	the variable as a global variable.
	 *	Could be potentially a way to
	 *	attack the system. Use std::get()
	 *	instead.
	 *
	 */
	static public function request( $info ){
		$arr = explode( ',', $info );
		foreach( $arr as $name ){
			$name = trim($name);
			$pos = strpos( $name, '=' );
			if( $pos > 0 ){
				$value = substr($name, $pos+1 ); 
				$name = substr($name, 0, $pos);
			}
			else $value = NULL;

			// case of multiple values...
			if( substr($name, -2) == "[]" ){
				// Simply removes the array signs
				$name = substr( $name, 0, -2 );
			}

			$v = self::get( $name );
			if(!$v) $v = $value;
			$GLOBALS[ self::normalizeVariableName($name) ] = $v;
		}
	}

	/**
	 * Retrieve the parameter from the request
	 * (works both with PUT and GET).
	 * 
	 * @param string $name the name of the parameter
	 * 		(case sensitive).
	 * @param string $value the default value if the
	 * 		parameter has not been given (non mandatory)
	 * @param bool $protect set to TRUE by default will
	 * 		replace "<" by "< " (this will avoid an interpretation
	 * 		by the browser -- note the protection is quite weak
	 * 		but sufficient for minimal protection).
	 * @return Ambigous <unknown, string>
	 */
	static public function get( $name, $defaultValue = null, $protect = TRUE ){
		global $ANGULAR_POST;
		$name = self::normalizeVariableName( $name );
		if (isset( $_REQUEST[$name]) ){
			$value = $_REQUEST[$name];
			if( $protect ){
				// Adding a space after the sign should protect the variable
				$value = str_replace( "<", "< ", $value);
			}
			return $value;
		}
		else if (@$ANGULAR_POST && isset( $ANGULAR_POST[$name]) ){
			return $ANGULAR_POST[$name];
		}
		return $defaultValue;
	}
	
	/**
	 * Convert an angular $http call to something compatible
	 * with JQuery call. Note this can be called even no
	 * AngularJS is used because the $_REQUEST values
	 * are used in preference when call std::get().
	 * 
	 * @param string $name the variable name
	 * @param string $value the default value.
	 * @return string the expected value
	 */
	static public function angularToPost(){
		global $ANGULAR_POST;
		$ANGULAR_POST = json_decode(file_get_contents('php://input'),true);
	}
	
	/**
	 * Checks if the array passed is associative. In PHP,
	 * there is no "associative" array in the way, there is no
	 * real way to distinguish them of a plain array having an 
	 * index starting at 0 and ending at (n-1) where n is the
	 * number of elements.
	 * 
	 * The code is based on:
	 * http://www.zomeoff.com/check-if-an-array-is-associative-or-sequentialindexed-in-php/
	 * 
	 * @param array $array an array to check.
	 * @return TRUE if the array is associative, False
	 * 		if the array is NULL, empty or not an array 
	 * 		at all.
	 */
	static public function is_associative( $array ){
		if( !is_array($array) ) return FALSE;
		$nb = count($array);
		if( $nb == 0 ) return FALSE;
		
		// Check on basic arrays
		return array_keys($array) !== range(0, $nb - 1);
	}
	
	
	/**
	 * Normalize the text provided. A normalized text
	 * is a text which contains only lowercase letters
	 * and digits.
	 *
	 *	This method can be used to create identifiers
	 *	based on user text. Note the accents variable
	 *	are not _yet_ concerned.
	 *
	 *	NOTE: do not rely on _strict_ conversion. This
	 *	method could be enhanced to deal with special characters.
	 *	Don't rely on getting strictly same results from
	 *	a version to another.
	 */ 
	static public function normalize( $str ){
		$buf = "";
		$str = trim( strtolower( $str ) );
		$len = strlen( $str );
		for( $i = 0; $i < $len; $i++ ){
			$c = $str[$i];
			if( ($c >= '0' && $c <= '9') || ($c >= 'a' && $c <= 'z') ){
				$buf .= $c;
			}
		}
		return $buf;
	}


	/**
	 * Create a password based on a limited number of
	 * characters. This is to avoid issues between
	 * some letters that are similar ("0" and "O" typically).
	 * 
	 * @param number $len the length of the password.
	 * @param string $str a string that contains authorized
	 * 		characters. A default one is available. 
	 * @return string the password.
	 */
	static public function makePassword( $len = 12, $str = "abcdefghjkpqrstxyz23456789AEFHJKLPRSTYZ" ){
		$ret = "";
		for( $i=0; $i < $len; $i++){
			$ret .= $str[ rand(0, strlen($str) -1) ];
		}
		return $ret;
	}

	static public function checkLuhn( $purportedCC ){
		$sum = 0;
		$nDigits = strlen( $purportedCC ); 
		$parity = $nDigits & 1;
		for( $i = nDigits-1; $i >= 0; $i-- ){
			$digit = intval( $purportedCC[$i] );
			if( ($i & 1) == $parity ){
				$digit = $digit * 2;
			}
			if( $digit > 9 ) $digit -= 9; 
			$sum = $sum + $digit;
		}
		return (($sum % 10) == 0);
	}

	/**
 	 *	Extend the text with the values stored in the
 	 *  $set array. This is a very _basic_ expander.
	 *	When possible, use Mustache or something more
	 *	complete.
	 *
	 *  @param string $data the data to expand
	 *  @param array $set the possible variable
	 *  @param string $expander a string of 3 characters for extension pattern.
	 *  
	 *  @return string $data expanded.
	 *
 	 */
	public static function expand( $data, $set, $expander = '${}' ){
		$len = strlen($data);
		$i = 0;
		$str = ""; 
		while($i < $len){
			$c = $data[$i++];
			if( $c == $expander[0] && $data[$i] == $expander[1] ){
				$filter = 0;
				$endchar = $expander[2];
				$varname = '';
				$i++;
				do {
					$c = $data[$i++];
					if( $c != $endchar ) $varname .= $c;
				} while( $c != $endchar );
				if( $varname[0] == "%" ){
					$varname = substr($varname,1);
					$filter = 1;
				}
				$values = explode('.', $varname);
				$value = $set;
				foreach( $values as $var0 ){
					if( is_array( $value ) ){
						$value = $value[ $var0 ];
					}
					else {
						$value = $value->$var0;
					}
				}
				
				switch( $filter ){
					case 1 : 
						$value = std::num2text( $value );
						break;
					default :
						break;
				}
				$str .= $value;
			}
			else {
				$str .= $c;
			}
		}
		return $str;
	}

	/**
	 * Exapnd from file.
	 * 
	 * @see std::expand
	 * @param unknown $filename
	 * @param unknown $set
	 */
	public static function expandFromFile( $filename, $set ){
		$txt = file_get_contents( $filename );
		return self::expand( $txt, $set );
	}

	/**
	 *	Get a link including an image rather than text.
	 *  @deprecated not suitable for quality programming.
	 *  
	 */
    public static function imgref( $img, $url, $params = array() ){
        $href = std::mkurl( $url, $params );
        $ret = std::tag('a', array('href'=>$href) )
                   . std::tag( "img", array("border"=>0, "class"=>"link", "src"=>$img) ) . "</img></a>";
        return $ret;
    }

	/**
	 * Converts a timestamp to a Javascript Date object. 
	 */
	public static function timestamp2js( $ts ){
		$ts = std::timestamp($ts); // ensure it is a timestamp
		$y = date('Y', $ts);
		$m = intval( date('n', $ts) - 1);
		$d = date('j', $ts);
		return "new Date( $y, $m, $d )";
	}

// 	/**
// 	 *	Converts an ISO date to a timestamp.
// 	 */
// 	public static function iso2local( string $isodt ) {
//         $date = DateTime::createFromFormat('Y-m-d', $isodt);
// 		return $date->getTimestamp();
// 	}

// 	/**
// 	 *	Intended for a local conversion of dates.
// 	 *
// 	 *	@deprecated DO NOT USE _ANYMORE_
// 	 */
//     public static function iso2local( $isodt, $lang = 'fr' ) {
//         $FORMATS = array('fr'=>'d/m/Y' );
//         $date = DateTime::createFromFormat('Y-m-d', $isodt);
//         return $date->format( $FORMATS[$lang] );
//     }

   
    public static function actionList( array $actions, $separator = "&nbsp;|&nbsp;" ) {
        $ret = "";
        $nbAction = 0;
        foreach( $actions as $action ){
            if( $nbAction > 0 ){
                $ret .= $separator;
            }
            list($icon, $url) = explode('|', $action);
            list($icon, $tooltip) = explode( ":", "$icon:" );
            if( $icon ){
                $img = std::tag("img", array("src"=>$icon, "border"=>0, "alt"=>$tooltip));
            }
            else {
                // No image provided
                $img = std::html($tooltip);
            }
            $ret .= std::tag("a", array("href"=>$url)) . $img . "</a>";
            $nbAction++;
        }  
        return $ret;
    }
    
    /**
     * Converts to HTML. Same as htmlspecialchars()
     * function from PHP.
     * 
     * @param unknown $str string to display
     * @return string text converted in HTML5.
     */
    public static function html( $str, $newlines = "<br>" ){
    	if( !is_array($str) ){
			// Should not be an array, but in case
    		$str = array($str);
    	}
    	
    	$ret = '';
    	$first_line = TRUE;
    	foreach( $str as $line ){
    		if( !$first_line ) $ret .= $newlines;
    		$ret .= htmlspecialchars( $line, ENT_COMPAT | ENT_HTML401, 'UTF-8' );
    		$first_line = FALSE;
    	}
    	return $ret;
    }
    
    /**
     * This function will clean up the HTML text.
     * BE CAREFUL: the resulting text must be espaced in
     * HTML to be displayed!
     * 
     * @param string $html the HTML to clean.
     * @return string the plain text.
     */
    public static function plain( $html ){
    	$ret = strip_tags($html);
    	$ret = html_entity_decode($ret, ENT_HTML401 | ENT_NOQUOTES, 'UTF-8');
    	return $ret;
    }
    
    /**
	 *	Convert a timestamp to an ISO date.
	 */
    public static function isodate( $dt = null ){
    	if (!$dt) $dt = time();
    	return date( 'Y-m-d\TH:i:s\Z', $dt );
    }
		
	/**
	 * Convert seconds to human readable text.
	 */
	public static function secs_to_h($secs) {
		$units = array (
			"week" => 7 * 24 * 3600,
			"day"  => 24 * 3600,
			"hour" => 3600,
			"min." => 60,
			"sec." => 1
		);
		
		// specifically handle zero
		if ($secs == 0){
			return "0 sec.";
		}
		else if ($secs < 1){
			return sprintf( "%1.3f ms", $secs );
		}
		
		$s = "";
		foreach ( $units as $name => $divisor ) {
			if ($quot = intval ( $secs / $divisor )) {
				$s .= "$quot $name ";
				// $s .= (abs ( $quot ) > 1 ? "s" : "") . ", ";
				$secs -= $quot * $divisor;
			}
		}
		
		return trim($s);
	}
	
	/**
	 *	Convert a timestamp to a DateTime.
	 *
	 *	@param ts the timestamp to convert
	 *	@return return a DateTime object.
	 */ 
	public static function datetime( $ts ){
		$dt = new DateTime();
		$ts = intval($timestamp);
		$dt->setTimestamp( $ts );
		return $dt;
	}
	
	/**
	 * Convert an UTF-8 string into its upper
	 * counterpart.
	 *
	 * @param string $value the value to convert.
	 * @return string the value in lowercase.
	 */
	public static function upper( $value ){
		return mb_strtoupper($value, 'UTF-8');
	}
	
	/**
	 * Convert an UTF-8 string into its lower 
	 * counterpart.
	 * 
	 * @param string $value the string to convert (in UTF8).
	 * @return string the value in lowercase.
	 */
	public static function lower( $value ){
		return mb_strtolower($value, 'UTF-8');
	}

	/**
	 * Computes the length of the string in characters.
	 * 
	 * @param string $str 
	 * @return number
	 */
	public static function len( $str ){
		return mb_strlen($str, 'UTF-8');
	}
	
	public static function endsWith( $full, $needle ){
		$l1 = std::len($full);
		$l2 = std::len($needle);
		if( $l2 > $l1 ) return FALSE;
		// return strcmp( substr );
		$e = mb_substr($full, $l1 - $l2, $l2);
		return $e === $needle;
		//return( $e && (std::len($e) == std::len($needle)) );
	}

	/**
	 * Checks if the $needle value begins the $str string.
	 * 
	 * @param string $str the string where to search.
	 * @param string $needle the string to search.
	 * @return TRUE is the string is stored at the start
	 * of the string.
	 * 
	 */
	public static function beginsWith( $str, $needle ){
		$len = strlen( $needle );
		return (strcmp( $needle, substr( $str, 0, $len ) ) == 0);
	}
	
	/**
	 * Capitalize the first character of the string.
	 * 
	 * @param string $text the string to capitalize. If
	 * 	NULL is passed, the returned string is an empty one.
	 * @return string the string capitalized.
	 * 		
	 */
	public static function capitalizeFirst($text){
		if( !$text ) return "";
		$text = trim( $text );
		if( strlen($text) < 1 ) return "";
		return std::upper(mb_substr($text,0,1,'UTF-8')) . mb_substr($text,1,NULL,'UTF-8');
	}
	
	public static function dump($a){
 		echo " -->";
 		echo '<pre>';
 		print_r($a);
 		echo "</pre>\n";
	}
	
	/**
	 * Convert the value in input to a valid
	 * timestamp. The input value can be a DateTime
	 * object or a plain timestamp or even null (in
	 * this case, we consider the current timestamp).
	 *
	 * @param <DateTime, int> $ts the value to translate in timestamp
	 * @return int a valid timestamp (seconds since 1st Jan 1970)
	 */
	public static function timestamp($dt){
		if( $dt == null ) return time();
		if( $dt instanceof \DateTime ){
			$dt = $dt->getTimestamp();
		}
		return intval($dt);
	}
	
	/**
	 * Clean of the text for real text output. Based
	 * on rules about encoding URLs. This function is intended
	 * to maximize the results given by the search engines.
	 *
	 * @param string $str the text to encode.
	 * @return string an encoded text without punctuation
	 * 		but accents and UTF8 characters are kept
	 * 		for a better view on search engines.
	 */
	public static function url_text($str){
		$clean = $str;
		$clean = std::lower($clean);
		if( std::len($clean) > 130 ){
			// Cut the text...!
			$clean = mb_substr( $clean, 0, 120, 'UTF-8' );
		}
		$search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
		$replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
		$clean = str_replace($search, $replace, $clean);
//  		if (function_exists('iconv')){
//  			// Add a second round for more complex stuff..
//  			$clean = iconv('UTF-8', 'ascii//IGNORE', $clean);
//  		}
		$clean = preg_replace("/[?\/\\&+'\"!,;:.()]/", ' ', $clean);
		$clean = preg_replace("/ +/", ' ', $clean);
		$clean = trim($clean);
		$clean = str_replace(' ', '-', $clean);
		$clean = preg_replace("/[-]+/", '-', $clean);
		$clean = urlencode($clean);
		// $clean = str_replace('%C3%A9', 'e', $clean);
		// $clean = str_replace('%C3%A8', 'e', $clean);
		// $clean = str_replace('%C3%A2', 'a', $clean);
		if( !$clean ) $clean = 'empty'; // avoid empty data
		return $clean;
	}
	
	/**
	 * Make a prety print output of a JSON string.
	 * 
	 * From http://stackoverflow.com/questions/6054033/pretty-printing-json-with-php
	 * 
	 * @param string $json a JSON string.
	 * @param string $html TRUE to have a HTML
	 *   output, FALSE will give you an ASCII output
	 *   to embed in a &lt;pre&gt; tag after escaped
	 *   it to std::html() for special chars.
	 * @return string the string (plain or HTML)
	 */
	public static function prettyJson($json, $html = false)
	{
		$result = '';
		$level = 0;
		$in_quotes = false;
		$in_escape = false;
		$ends_line_level = NULL;
		$json_length = strlen( $json );
	
		for( $i = 0; $i < $json_length; $i++ ) {
			$char = $json[$i];
			$new_line_level = NULL;
			$post = "";
			if( $ends_line_level !== NULL ) {
				$new_line_level = $ends_line_level;
				$ends_line_level = NULL;
			}
			if ( $in_escape ) {
				$in_escape = false;
			} else if( $char === '"' ) {
				$in_quotes = !$in_quotes;
			} else if( ! $in_quotes ) {
				switch( $char ) {
					case '}': 
					case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;
	
					case '{': 
					case '[':
						$level++;
					case ',':
						$ends_line_level = $level;
						break;
	
					case ':':
						$post = " ";
						break;
	
					case " ": 
					case "\t":
					case "\n": 
					case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
				}
			} else if ( $char === '\\' ) {
				$in_escape = true;
			}
			if( $new_line_level !== NULL ) {
				$result .= "\n".str_repeat( "\t", $new_line_level );
			}
			$result .= $char.$post;
		}
	
		return $result;
	}
	
	/**
	 * 
	 * @param string $text the text to ellipsis
	 * @param int $max_length the maximum length you accept.
	 */
	public static function ellipsis($text, $max_length)
	{
		if( $max_length < 10 ) $max_length = 10; 
		if( std::len($text) > $max_length ){
			$text = mb_substr($text, 0, $max_length - 5, 'utf-8');
			$text = trim($text);
			$text .= "...";
		}
		return $text;
	}
	
	/**
	 * Ensure a value is stored in a array.
	 * 
	 * @param mixed $value the value
	 * 
	 * @return array an array with all the values. If
	 * $value is NULL, the returned value is an empty
	 * array. If $value was already an array, return it
	 * without change. In all other cases, returns an
	 * array of one element (the element is $value). 
	 * 
	 */
	public static function to_array($value)
	{
		if( is_array($value) ) return $value;
		if( $value === NULL ) return [];
		return [ $value ];
	}
	
}


