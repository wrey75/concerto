<?php

namespace Concerto;
	
/**
 * A light, permissions-checking logging class. Based
 * on a code from Kenneth Katzgrau <katzgrau@gmail.com>
 * (July 26, 2008).
 * 
 *
 * Usage: 
 *		$log = new Logger ( "log.txt" , Logger::INFO );
 *		$log->info("Returned a million search results");	//Prints to the log file
 *		$log->fatal("Oh dear.");				//Prints to the log file
 *		$log->debug("x = 5");					//Prints nothing due to priority setting
 *
 * Comments: 
 *    Originally written for use with wpSearch. The code has been
 *    enhanced for the Concerto library to provide a
 *    log information.
 * 
 * IMPORTANT NOTE: NEVER LOG SENSIBLE INFORMATION
 * LIKE PASSWORDS OR CREDENTIALS -- EVEN IN DEBUG
 * MODE.
 * 
 * @since v0.2 
 * 
 */	
class Logger {

	public static $defaultLog; // A default logger
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
	protected $now;
	protected $log_file;
	protected $sec; // Last log timestamp
	protected $usec; // microseconds (<1.0)
			
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
		$this->format = "%T%f - %5l --> %m";
		$this->MessageQueue = array();
		$this->userName = "<guest>";
		$this->priority = $priority;
		if( is_array($filepath)){
			$arr = $filepath;
			$this->log_file = @$arr['file'];
			if( isset($arr['level']) ){
				$this->priority = $arr['level'];
			}
			if( isset($arr['user']) ){
				$this->userName = $arr['user'];
			}
			if( isset($arr['format']) ){
				$this->setFormat( $arr['format'] );
			}
		}
		else {
			$this->log_file = $filepath;
		}
		
		if ( file_exists( $this->log_file ) ){
			if ( !is_writable($this->log_file) ){
				$this->MessageQueue[] = "The file exists, but could not be opened for writing. Check that appropriate permissions have been set.";
				return;
			}
		}
	}
	

	/**
	 * Information log. Should be used to display information
	 * useful for the understanding the program (usually gives
	 * non-confidential configuration information).
	 * 
	 * @param string $txt text to show
	 */
	public function info($txt)
	{
		$this->Log( $txt , Logger::INFO );
	}
		
	/**
	 * Debug log. Should be used to display debugging
	 * information intended for the developper only.
	 * 
	 * @param string $txt text to show
	 */
	public function debug($txt)
	{
		$this->Log( $txt , Logger::DEBUG );
	}
	

	/**
	 * Warning log. Something wrong or abnormal
	 * but can be workarounded automatically or,
	 * if not, can be ignored.
	 *
	 * @param string $txt text to show
	 */
	public function warn($txt)
	{
		$this->Log( $line , Logger::WARNING );	
	}


	/**
	 * Error log. Something wrong and need
	 * an external maintenance (disk is full,
	 * can not access database...). The program
	 * is not stopped.
	 *
	 * @param string $txt text to show
	 */
	public function error($txt)
	{
		$this->Log( $txt, Logger::ERROR );		
	}

	/**
	 * Log a fatal error. The program MUST
	 * be aborted immediatly with a completion
	 * error.
	 * 
	 * The abort of the program MUST be done
	 * by the caller.
	 * 
	 * @param string $txt text to show
	 */
	public function fatal($txt)
	{
		$this->Log( $txt, Logger::FATAL );
	}
	
	/**
	 * Check if the debug() will ouput something.
	 * 
	 * @return true if the log is displayed.
	 */
	public function isDebugEnabled(){
		return ( $this->priority <= Logger::DEBUG );
	}
	
	/**
	 * Check if the info() will ouput something.
	 * 
	 * @return true if the log is displayed.
	 */
	 public function isInfoEnabled(){
		return ( $this->priority <= Logger::INFO );
	}
	
	/**
	 * Check if the warn() will ouput something.
	 * 
	 * @return true if the log is displayed.
	 */
	public function isWarnEnabled(){
		return ( $this->priority <= Logger::WARN );
	}
	
	/**
	 * @deprecated use isWarnEnabled() instead.
	 * 
	 */
	public function isWarningEnabled(){
		return $this->isWarnEnabled();
	}
	
	/**
	 * Check if the error() will ouput something.
	 * 
	 * @return true if the log is displayed.
	 */
	public function isErrorEnabled(){
		return ( $this->priority <= Logger::ERROR );
	}
	
	/**
	 * Log the text provided.
	 *
	 * @param string $line the line of log to display
	 * @param int $priority the priority (use 
	 * 	a constant when possible like Logger::ERROR).
	 */
	protected function Log($line, $priority)
	{
		if ( $this->priority <= $priority )
		{
			list($this->usec, $this->sec) = explode(" ", microtime());
			
			// We explode the contents by lines to
			// keep the status at the beginning of
			// the line valid. Note, we resuse the same status
			// for each line (that makes sense).
			$lines = explode("\n", $line);

			$freeLine = '';
			foreach( $lines as $i => $txt ){
				$freeLine .= $this->getFormattedText( $priority, $txt ) . "\n";
			}
			$this->WriteFreeFormLine( $freeLine );
		}
	}
	
	/**
	 * Write the text provided to the disk. The
	 * text must be already formatted.
	 * 
	 * @param string $line the data to log.
	 */
	protected function WriteFreeFormLine( $line )
	{
		if( $this->log_file == "syslog:" ){
			// Send to the system log
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
				$this->MessageQueue[] = 'LOG: Can not open "{$this->log_file}"';
				$this->MessageQueue[] = $line;
			}
		}
	}


// 	/**
// 	 * Format the date to be added in the log file.
// 	 * 
// 	 * @return the formatted date.
// 	 */
// 	protected function getFormattedDate(){
// 		$format = ($this->withDate ? "Y-m-d " : "" ) . "H:i:s";
// 		$t = date( $format );
// 		list($usec, $sec) = explode(" ", microtime());
// 		$t .= substr( sprintf("%0.3f", (float)$usec), 1);
// 		return $t;
// 	}

	/**
	 * Set the user name. The user name is used
	 * when the %u is encountred in the format.
	 * 
	 * @param string $username the user name.
	 */
	public function setUser( $username ){
		$this->userName = $username;
	}
	
// 	private function getTimeLine( $level )
// 	{
// 		$i = 0;
// 		$len = strlen( $this->format );
// 		$ret = "";
// 		while( $i < $len ){
// 			$c = $this->format[$i];
// 			if( $c == '%' ){
// 				$i++;
// 				$size = 0;
// 				while( $this->format[$i] >= '0' && $this->format[$i] < '9' ){
// 					$size = $size * 10 + ($this->format[$i] - '0'); 
// 					$i++;
// 				}
// 				$type = $this->format[$i++];
// 				switch( $type ){
// 					case 'd' : // Date
// 						$t = $this->getFormattedDate();
// 						break;
// 					case 'u' : // User (must be provided)
// 						$t = $this->userName;
// 						break;
// 					case 'l' : // Log level
// 						$t = self::getLevelAsString($level);
// 						break;			
// 					default :
// 						$t = "???";
// 						break;
// 				}	
// 				if( $size > 0 ) {
// 					// Complete with spaces or trunk...
// 					$t = substr($t . str_repeat(" ", $size), 0, $size );
// 				}
// 				$ret .= $t;
// 			}
// 			else {
// 				$ret .= $c;
// 				$i++;
// 			}
// 		}
// 		return $ret;
// 	}
	

	/**
	 * Set the log format. You can add special variables:
	 * 
	 * %D : The date (formatted YYYY-MM-DD)
	 * 
	 * %T : The time (formatted HH:MM:SS)
	 * 
	 * %f : The microseconds (including the dot)
	 * 
	 * %u : User (must be provided)
	 * 
	 * %l : The level of the log (expressed as a string)
	 * 
	 * %m : The message. If not provided, we automatically
	 * add the message at the end of the format.
	 *
	 * @param string $fmt the format for the logs.
	 */
	public function setFormat( $fmt ){
		$this->format = $fmt;
	}
	
	/**
	 * Formats the line.
	 * 
	 * @param int $level the log level.
	 * @param string $text the text to include.
	 * 
	 * @return the text to write.
	 */
	private function getFormattedText( $level, $text )
	{
		$messageAdded = false;
		$i = 0;
		$len = strlen( $this->format );
		$ret = "";
		while( $i < $len ){
			$c = $this->format[$i];
			if( $c == '%' ){
				$i++;
				$size = 0;
				
				// Check if a size is given for the type
				while( $this->format[$i] >= '0' && $this->format[$i] < '9' ){
					$size = $size * 10 + ($this->format[$i] - '0');
					$i++;
				}

				// Add the information
				$type = $this->format[$i++];
				switch( $type ){
					case 'D' : // Day (ISO Format)
						$size = 0;
						$t = date( "Y-m-d", $this->sec);
						break;

					case 'T' : // Time (ISO Format: HH:MM:SS)
						$size = 0;
						$t = date( "H:i:s", $this->sec);
						break;

					case 'f' : // micro-seconds (3 characters)
						$size = 0;
						$t = sprintf("%0.3", $this->usec);
						break;
							
					case 'u' : // User (must be provided)
						$t = $this->userName;
						break;
	
					case 'l' : // Log level
						$t = self::getLevelAsString($level);
						break;

					case 'm' : // Log level
						$messageAdded = true;
						$t = $text;
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
		
		if(!$messageAdded){
			// Add the message even not explicitly added in the format.
			$ret .= " $text";
		}
		return $ret;
	}
	
	
	/**
	 * Convert the log levl as a string.
	 * 
	 * @param int $level the log level
	 * @return string the level name.
	 */
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

