<?php

namespace Concerto;
	
/**
 * A light, permissions-checking logging class. 
 * 
 * Author	: Kenneth Katzgrau <katzgrau@gmail.com>
 * Date	    : July 26, 2008
 * 
 * Website	: http://codefury.net
 * Version	: 1.0
 *
 * Usage: 
 *		$log = new KLogger ( "log.txt" , Logger::INFO );
 *		$log->info("Returned a million search results");	//Prints to the log file
 *		$log->fatal("Oh dear.");				//Prints to the log file
 *		$log->debug("x = 5");					//Prints nothing due to priority setting
 *
 * Comments: 
 *    Originally written for use with wpSearch. The code is
 *    now adapted and enhanced by OxANDE to provide a
 *    log information.
 * 
 */	
class Logger {
	// The default logger.
	public static $defaultLog;
	public $withDate = FALSE;
	public $priority;
	public $MessageQueue;
		
	// logging levels described by RFC 5424.
	const DEBUG     = 100;	// Detailed debug information.
	const INFO      = 200;	// Interesting events. Examples: User logs in, SQL logs.
	const NOTICE    = 250;  // Normal but significant events.
	const WARNING   = 300;	// Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
	const ERROR     = 400;	// Runtime errors that do not require immediate action but should typically be logged and monitored.
	const CRITICAL  = 500;	// Critical conditions. Example: Application component unavailable, unexpected exception.
	const ALERT     = 550;	// Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
	const EMERGENCY = 600;	// Emergency: system is unusable.
		
	protected $format;
	protected $log_file;
			
	/**
	 * Return an instance with no log capabilities
	 * (messages are simply ignored).
	 */
	public static function getNull(){
		return new Logger(null, 1000);
	} 
		
	/**
	 * Creates a logger.
	 * @param string $filepath the filename where data is saved.
	 * 		Can be "stdout" in addition of the basic filenames.
	 * @param int $priority the priority. Use the constants 
	 * 		rather than direct values.
	 */
	public function __construct( $filepath = "stdout:", $priority = Logger::INFO )
	{
		
		$this->format = "%d - %5l --> ";
		$this->log_file = $filepath;
		$this->MessageQueue = array();
		$this->userName = "<guest>";
		$this->priority = $priority;
			
		if ( file_exists( $this->log_file ) ){
			if ( !is_writable($this->log_file) ){
				$this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
				return;
			}
		}
	}
	

	public function setFormat( $fmt ){
		$this->format = $fmt;
	}
	
		
	public function info($line)
	{
		$this->Log( $line , Logger::INFO );
	}
		
	public function debug($line)
	{
		$this->Log( $line , Logger::DEBUG );
	}
		
	public function warn($line)
	{
		$this->Log( $line , Logger::WARNING );	
	}
	
	public function error($line)
	{
		$this->Log( $line , Logger::ERROR );		
	}

	/**
	 * Log a fatal error. Used by the fatalError()
	 * function if a log is available...
	 * 
	 * @param unknown_type $line
	 */
	public function fatal($line)
	{
		$this->Log( $line , Logger::FATAL );
	}
	
	
	public function isDebugEnabled(){
		return ( $this->priority <= self::DEBUG );
	}
	
	public function isInfoEnabled(){
		return ( $this->priority <= self::INFO );
	}
	
	public function isWarningEnabled(){
		return ( $this->priority <= self::WARN );
	}
	
	public function isErrorEnabled(){
		return ( $this->priority <= self::ERROR );
	}
		
	protected function Log($line, $priority)
	{

		
		if ( $this->priority <= $priority )
		{
			
			$status = $this->getTimeLine( $priority );
			
			// We explode the contents by lines to
			// keep the status at the beginning of
			// the line valid. Note, we resuse the same status
			// for each line (that makes sense).
			$lines = explode("\n", $line);

			foreach( $lines as $i => $txt ){
				$this->WriteFreeFormLine( "$status$txt\n" );
				$status = str_repeat( " ", strlen($status) );
			}
		}
	}
	
	public function WriteFreeFormLine( $line )
	{
		if( $this->log_file == "syslog:" ){
			syslog(LOG_WARNING, $line);
		}
		else if( $this->log_file == "stdout:" ){
			echo "\n** $line\n";
		}
		else {

			
			$fic = fopen( $this->log_file, "a" );
			if( $fic ){
				fwrite( $fic, $line );
				fclose( $fic );
			}
			else {
				// echo "*** $this->log_file || $line ***<pre>"; debug_print_backtrace(); exit;
				$this->MessageQueue[] = 'LOG: Can not open "{$this->log_file}"';
				$this->MessageQueue[] = $line;
			}
		}
	}
	
	protected function getFormattedDate(){
		$format = ($this->withDate ? "Y-m-d " : "" ) . "H:i:s";
		$t = date( $format );
		list($usec, $sec) = explode(" ", microtime());
		$t .= substr( sprintf("%0.3f", (float)$usec), 1);
		return $t;
	}

	public function setUser( $username ){
		$this->userName = $username;
	}
		
	private function getTimeLine( $level )
	{
		$i = 0;
		$len = strlen( $this->format );
		$ret = "";
		while( $i < $len ){
			$c = $this->format[$i];
			if( $c == '%' ){
				$i++;
				$size = 0;
				while( $this->format[$i] >= '0' && $this->format[$i] < '9' ){
					$size = $size * 10 + ($this->format[$i] - '0'); 
					$i++;
				}
				
				$type = $this->format[$i++];
				switch( $type ){
					case 'd' : // Date
						$t = $this->getFormattedDate();
						break;
						
					case 'u' : // User (must be provided)
						$t = $this->userName;
						break;
						
					case 'l' : // Log level
						$t = self::getLevelAsString($level);
						break;
						
					default :
						$t = "???";
						break;
				}
				
				if( $size > 0 ) {
					// Complete with spaces or trunk...
					$t = substr($t . str_repeat(" ", $size), 0, $size );
				}
				$ret .= $t;
			}
			else {
				$ret .= $c;
				$i++;
			}
		}
		return $ret;
	}
	
	public static function getLevelAsString($level){
		if( $level >= self::CRITICAL ){
			return "FATAL";
		}
		else if( $level >= self::ERROR ){
			return "ERROR";
		}
		else if( $level >= self::WARNING ){
			return "WARN";
		}
		else if( $level >= self::INFO ){
			return "INFO";
		}
		return "DEBUG";
	}
	
	/**
	 * Old version.
	 * 
	 * @param unknown_type $level
	 */
	private function getTimeLine2( $level )
	{
		$time = $this->getFormattedDate();
		return "$time - " . substr(self::getLevelAsString($level)) . " -->";
	}
		
	/**
	 * Returns the default logger (you have to set it manually).
	 * This will avoid to use a global variable for the logs.
	 * 
	 * If no default log has been initialized, returns a "null"
	 * logger (i.e. the equivalent to /dev/null).
	 */
	public static function getDefault(){
		if( !isset(static::$defaultLog) ){
			self::$defaultLog = self::getNull();
		}
		return static::$defaultLog;
	}
}

