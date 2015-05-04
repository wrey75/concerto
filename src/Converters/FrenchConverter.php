<?php

namespace \Concerto\Converters;


class FrenchConverter {

	/**
	 * Convert to a written digit. Works only for
	 * french currently. Used internally.
	 * 
	 * @param unknown $num a number from one to 19.
	 * @return string
	 */
	protected static function digit1text( $num ){
		switch( $num ){
			case 1 : return "un";
			case 2 : return "deux";
			case 3 : return "trois";
			case 4 : return "quatre";
			case 5 : return "cinq";
			case 6 : return "six";
			case 7 : return "sept";
			case 8 : return "huit";
			case 9 : return "neuf";
			case 10 : return "dix";
			case 11 : return "onze";
			case 12 : return "douze";
			case 13 : return "treize";
			case 14 : return "quatorze";
			case 15 : return "quinze";
			case 16 : return "seize";
			case 17 : return "dix-sept";
			case 18 : return "dix-huit";
			case 19 : return "dix-neuf";
			default : return "!";
		}
	}

	protected static function int3text( $num ){
		$ret = "";
		$digit = ($num / 100) % 10;
		$remain = ($num % 100);
		if( $digit > 1 ){
			$ret = self::digit1text( $digit ) . " cent";
			if( $remain == 0 ) $ret .= "s";
		}
		else if( $digit == 1) {
			$ret = "cent";
		}
		if( $remain > 0 && $digit > 0) $ret .= " ";

		if( $remain >= 80 ){
			$ret .= "quatre-vingt";
			$dig = $remain - 80;
		}
		else if( $remain >= 60 ){
			$ret .= "soixante";
			$dig = $remain - 60;
		}
		else if( $remain >= 50 ){
			$ret .= "cinquante";
			$dig = $remain - 50;
		}
		else if( $remain >= 40 ){
			$ret .= "quarante";
			$dig = $remain - 40;
		}
		else if( $remain >= 30 ){
			$ret .= "trente";
			$dig = $remain - 30;
		}
		else if( $remain >= 20 ){
			$ret .= "vingt";
			$dig = $remain - 20;
		}
		else {
			$dig = $remain;
		}

		if( $dig > 0 ){
			switch( $remain ){
				case 61 :
				case 51 :
				case 41 :
				case 31 :
				case 21 :
					$ret .= " et un";
					break;
				case 71 :
					$ret .= " et onze";
					break;
				default :
					if( $remain > 20 ) $ret .= "-";
					$ret .= self::digit1text( $dig );
					break; 
			}
		}
		return $ret;
	}

	/**
	 * Converts a number to its written counterpart.
	 * Works only for French language curretly.
	 * 
	 * @param int $num a number.
	 */
	public static function num2text( $num ){
		$ret = "";
		if( $num >= 1000 ){
			if( $num >= 2000 ){
				$ret = self::int3text( $num / 1000 ) . " ";
			}
			$ret .= "mille ";
			$num = $num % 1000;
		}
		$ret .= self::int3text( $num );
		return trim($ret);
	}

}

