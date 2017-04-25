<?php

namespace Concerto;


use Symfony\Component\Validator\Constraints\DateTime;
use phpDocumentor\Descriptor\Builder\Reflector\Tags\UsesAssembler;
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

	const BASIC_CONVERTER = [
			"(" => "(",
			")" => ")",
			"[" => "(",
			"]" => ")",
			"²" => "2",
			"\xEF\xBD\x82" => "b", // FULLWIDTH LATIN SMALL LETTER B

			"!" => "",
			"¨" => "-",
			'─' => "-",
			'—' => "-",
			'­' => "-",
			'°' => ".",
			'•' => "-",
			'“' => "-",
			'ñ' => "n",


// 			"\xc2\xa0" => '', //U+00A0	  	NO-BREAK SPACE
// 			"\xc2\xa1" => '!', //U+00A1	¡ 	INVERTED EXCLAMATION MARK
// 			"\xc2\xa2" => '', //U+00A2	¢ 	CENT SIGN
// 			"\xc2\xa3" => '', //U+00A3	£ 	POUND SIGN
// 			"\xc2\xa4" => '', //U+00A4	¤ 	CURRENCY SIGN
 			"\xc2\xa5" => 'Y', //U+00A5	¥ 	YEN SIGN
// 			"\xc2\xa6" => '', //U+00A6	¦ 	BROKEN BAR
// 			"\xc2\xa7" => '', //U+00A7	§ 	SECTION SIGN
// 			"\xc2\xa8" => '', //U+00A8	¨ 	DIAERESIS
			"\xc2\xa9" => '(c)', //U+00A9	© 	COPYRIGHT SIGN
			"\xc2\xaa" => 'e', //U+00AA	ª 	FEMININE ORDINAL INDICATOR
			"\xc2\xab" => '', //U+00AB	« 	LEFT-POINTING DOUBLE ANGLE QUOTATION MARK
//			"\xc2\xac" => '', //U+00AC	¬ 	NOT SIGN
			"\xc2\xad" => '', //U+00AD	 	SOFT HYPHEN
			"\xc2\xae" => '(r)', //U+00AE	® 	REGISTERED SIGN
			"\xc2\xaf" => '', //U+00AF	¯ 	MACRON
			"\xc2\xb0" => '', //U+00B0	° 	DEGREE SIGN
			"\xc2\xb1" => '', //U+00B1	± 	PLUS-MINUS SIGN
			"\xc2\xb2" => '2', //U+00B2	² 	SUPERSCRIPT TWO
			"\xc2\xb3" => '3', //U+00B3	³ 	SUPERSCRIPT THREE
			"\xc2\xb4" => '', //U+00B4	´ 	ACUTE ACCENT
			"\xc2\xb5" => 'u', //U+00B5	µ 	MICRO SIGN
			"\xc2\xb6" => 'pi', //U+00B6	¶ 	PILCROW SIGN
			"\xc2\xb7" => '-', //U+00B7	· 	MIDDLE DOT
			"\xc2\xb8" => '', //U+00B8	¸ 	CEDILLA
			"\xc2\xb9" => '1', //U+00B9	¹ 	SUPERSCRIPT ONE
			"\xc2\xba" => '', //U+00BA	º 	MASCULINE ORDINAL INDICATOR
			"\xc2\xbb" => '', //U+00BB	» 	RIGHT-POINTING DOUBLE ANGLE QUOTATION MARK
			"\xc2\xbc" => '', //U+00BC	¼ 	VULGAR FRACTION ONE QUARTER
			"\xc2\xbd" => '', //U+00BD	½ 	VULGAR FRACTION ONE HALF
			"\xc2\xbe" => '', //U+00BE	¾ 	VULGAR FRACTION THREE QUARTERS
			"\xc2\xbf" => '', //U+00BF	¿ 	INVERTED QUESTION MARK
			"\xc3\x80" => 'A', //U+00C0	À 	LATIN CAPITAL LETTER A WITH GRAVE
			"\xc3\x81" => 'A', //U+00C1	Á 	LATIN CAPITAL LETTER A WITH ACUTE
			"\xc3\x82" => 'A', //U+00C2	Â 	LATIN CAPITAL LETTER A WITH CIRCUMFLEX
			"\xc3\x83" => 'A', //U+00C3	Ã 	LATIN CAPITAL LETTER A WITH TILDE
			"\xc3\x84" => 'A', //U+00C4	Ä 	LATIN CAPITAL LETTER A WITH DIAERESIS
			"\xc3\x85" => 'A', //U+00C5	Å 	LATIN CAPITAL LETTER A WITH RING ABOVE
			"\xc3\x86" => 'AE', //U+00C6	Æ 	LATIN CAPITAL LETTER AE
			"\xc3\x87" => 'C', //U+00C7	Ç 	LATIN CAPITAL LETTER C WITH CEDILLA
			"\xc3\x88" => 'E', //U+00C8	È 	LATIN CAPITAL LETTER E WITH GRAVE
			"\xc3\x89" => 'E', //U+00C9	É 	LATIN CAPITAL LETTER E WITH ACUTE
			"\xc3\x8a" => 'E', //U+00CA	Ê 	LATIN CAPITAL LETTER E WITH CIRCUMFLEX
			"\xc3\x8b" => 'E', //U+00CB	Ë 	LATIN CAPITAL LETTER E WITH DIAERESIS
			"\xc3\x8c" => 'I', //U+00CC	Ì 	LATIN CAPITAL LETTER I WITH GRAVE
			"\xc3\x8d" => 'I', //U+00CD	Í 	LATIN CAPITAL LETTER I WITH ACUTE
			"\xc3\x8e" => 'I', //U+00CE	Î 	LATIN CAPITAL LETTER I WITH CIRCUMFLEX
			"\xc3\x8f" => 'I', //U+00CF	Ï 	LATIN CAPITAL LETTER I WITH DIAERESIS
			// "\xc3\x90" => '', //U+00D0	Ð 	LATIN CAPITAL LETTER ETH
			"\xc3\x91" => 'N', //U+00D1	Ñ 	LATIN CAPITAL LETTER N WITH TILDE
			"\xc3\x92" => 'O', //U+00D2	Ò 	LATIN CAPITAL LETTER O WITH GRAVE
			"\xc3\x93" => 'O', //U+00D3	Ó 	LATIN CAPITAL LETTER O WITH ACUTE
			"\xc3\x94" => 'O', //U+00D4	Ô 	LATIN CAPITAL LETTER O WITH CIRCUMFLEX
			"\xc3\x95" => 'O', //U+00D5	Õ 	LATIN CAPITAL LETTER O WITH TILDE
			"\xc3\x96" => 'O', //U+00D6	Ö 	LATIN CAPITAL LETTER O WITH DIAERESIS
			"\xc3\x97" => 'x', //U+00D7	× 	MULTIPLICATION SIGN
			"\xc3\x98" => '0', //U+00D8	Ø 	LATIN CAPITAL LETTER O WITH STROKE
			"\xc3\x99" => 'U', //U+00D9	Ù 	LATIN CAPITAL LETTER U WITH GRAVE
			"\xc3\x9a" => 'U', //U+00DA	Ú 	LATIN CAPITAL LETTER U WITH ACUTE
			"\xc3\x9b" => 'U', //U+00DB	Û 	LATIN CAPITAL LETTER U WITH CIRCUMFLEX
			"\xc3\x9c" => 'U', //U+00DC	Ü 	LATIN CAPITAL LETTER U WITH DIAERESIS
			"\xc3\x9d" => 'Y', //U+00DD	Ý 	LATIN CAPITAL LETTER Y WITH ACUTE
			// "\xc3\x9e" => '', //U+00DE	Þ 	LATIN CAPITAL LETTER THORN
			"\xc3\x9f" => 'ss', //U+00DF	ß 	LATIN SMALL LETTER SHARP S
			"\xc3\xa0" => 'a', //U+00E0	à 	LATIN SMALL LETTER A WITH GRAVE
			"\xc3\xa1" => 'a', //U+00E1	á 	LATIN SMALL LETTER A WITH ACUTE
			"\xc3\xa2" => 'a', //U+00E2	â 	LATIN SMALL LETTER A WITH CIRCUMFLEX
			"\xc3\xa3" => 'a', //U+00E3	ã 	LATIN SMALL LETTER A WITH TILDE
			"\xc3\xa4" => 'a', //U+00E4	ä 	LATIN SMALL LETTER A WITH DIAERESIS
			"\xc3\xa5" => 'a', //U+00E5	å 	LATIN SMALL LETTER A WITH RING ABOVE
			"\xc3\xa6" => 'ae', //U+00E6	æ 	LATIN SMALL LETTER AE
			"\xc3\xa7" => 'c', //U+00E7	ç 	LATIN SMALL LETTER C WITH CEDILLA
			"\xc3\xa8" => 'e', //U+00E8	è 	LATIN SMALL LETTER E WITH GRAVE
			"\xc3\xa9" => 'e', //U+00E9	é 	LATIN SMALL LETTER E WITH ACUTE
			"\xc3\xaa" => 'e', //U+00EA	ê 	LATIN SMALL LETTER E WITH CIRCUMFLEX
			"\xc3\xab" => 'e', //U+00EB	ë 	LATIN SMALL LETTER E WITH DIAERESIS
			"\xc3\xac" => 'i', //U+00EC	ì 	LATIN SMALL LETTER I WITH GRAVE
			"\xc3\xad" => 'i', //U+00ED	í 	LATIN SMALL LETTER I WITH ACUTE
			"\xc3\xae" => 'i', //U+00EE	î 	LATIN SMALL LETTER I WITH CIRCUMFLEX
			"\xc3\xaf" => 'i', //U+00EF	ï 	LATIN SMALL LETTER I WITH DIAERESIS
// 			"\xc3\xb0" => '', //U+00F0	ð 	LATIN SMALL LETTER ETH
			"\xc3\xb1" => 'n', //U+00F1	ñ 	LATIN SMALL LETTER N WITH TILDE
			"\xc3\xb2" => 'o', //U+00F2	ò 	LATIN SMALL LETTER O WITH GRAVE
			"\xc3\xb3" => 'o', //U+00F3	ó 	LATIN SMALL LETTER O WITH ACUTE
			"\xc3\xb4" => 'o', //U+00F4	ô 	LATIN SMALL LETTER O WITH CIRCUMFLEX
			"\xc3\xb5" => 'o', //U+00F5	õ 	LATIN SMALL LETTER O WITH TILDE
			"\xc3\xb6" => 'o', //U+00F6	ö 	LATIN SMALL LETTER O WITH DIAERESIS
// 			"\xc3\xb7" => '', //U+00F7	÷ 	DIVISION SIGN
			"\xc3\xb8" => 'o', //U+00F8	ø 	LATIN SMALL LETTER O WITH STROKE
			"\xc3\xb9" => 'u', //U+00F9	ù 	LATIN SMALL LETTER U WITH GRAVE
			"\xc3\xba" => 'u', //U+00FA	ú 	LATIN SMALL LETTER U WITH ACUTE
			"\xc3\xbb" => 'u', //U+00FB	û 	LATIN SMALL LETTER U WITH CIRCUMFLEX
			"\xc3\xbc" => 'u', //U+00FC	ü 	LATIN SMALL LETTER U WITH DIAERESIS
			"\xc3\xbd" => 'y', //U+00FD	ý 	LATIN SMALL LETTER Y WITH ACUTE
// 			"\xc3\xbe" => '', //U+00FE	þ 	LATIN SMALL LETTER THORN
			"\xc3\xbf" => 'y', //U+00FF	ÿ 	LATIN SMALL LETTER Y WITH DIAERESIS	

			"\xc4\x80" => 'A', //U+0100	Ā 	LATIN CAPITAL LETTER A WITH MACRON
			"\xc4\x81" => 'a', //U+0101	ā 	LATIN SMALL LETTER A WITH MACRON
			"\xc4\x82" => 'A', //U+0102	Ă 	LATIN CAPITAL LETTER A WITH BREVE
			"\xc4\x83" => 'a', //U+0103	ă 	LATIN SMALL LETTER A WITH BREVE
			"\xc4\x84" => 'A', //U+0104	Ą 	LATIN CAPITAL LETTER A WITH OGONEK
			"\xc4\x85" => 'a', //U+0105	ą 	LATIN SMALL LETTER A WITH OGONEK
			"\xc4\x86" => 'C', //U+0106	Ć 	LATIN CAPITAL LETTER C WITH ACUTE
			"\xc4\x87" => 'c', //U+0107	ć 	LATIN SMALL LETTER C WITH ACUTE
			"\xc4\x88" => 'C', //U+0108	Ĉ 	LATIN CAPITAL LETTER C WITH CIRCUMFLEX
			"\xc4\x89" => 'c', //U+0109	ĉ 	LATIN SMALL LETTER C WITH CIRCUMFLEX
			"\xc4\x8a" => 'C', //U+010A	Ċ 	LATIN CAPITAL LETTER C WITH DOT ABOVE
			"\xc4\x8b" => 'c', //U+010B	ċ 	LATIN SMALL LETTER C WITH DOT ABOVE
			"\xc4\x8c" => 'C', //U+010C	Č 	LATIN CAPITAL LETTER C WITH CARON
			"\xc4\x8d" => 'c', //U+010D	č 	LATIN SMALL LETTER C WITH CARON
			"\xc4\x8e" => 'D', //U+010E	Ď 	LATIN CAPITAL LETTER D WITH CARON
			"\xc4\x8f" => 'd', //U+010F	ď 	LATIN SMALL LETTER D WITH CARON
			"\xc4\x90" => 'D', //U+0110	Đ 	LATIN CAPITAL LETTER D WITH STROKE
			"\xc4\x91" => 'd', //U+0111	đ 	LATIN SMALL LETTER D WITH STROKE
			"\xc4\x92" => 'E', //U+0112	Ē 	LATIN CAPITAL LETTER E WITH MACRON
			"\xc4\x93" => 'e', //U+0113	ē 	LATIN SMALL LETTER E WITH MACRON
			"\xc4\x94" => 'E', //U+0114	Ĕ 	LATIN CAPITAL LETTER E WITH BREVE
			"\xc4\x95" => 'e', //U+0115	ĕ 	LATIN SMALL LETTER E WITH BREVE
			"\xc4\x96" => 'E', //U+0116	Ė 	LATIN CAPITAL LETTER E WITH DOT ABOVE
			"\xc4\x97" => 'e', //U+0117	ė 	LATIN SMALL LETTER E WITH DOT ABOVE
			"\xc4\x98" => 'E', //U+0118	Ę 	LATIN CAPITAL LETTER E WITH OGONEK
			"\xc4\x99" => 'e', //U+0119	ę 	LATIN SMALL LETTER E WITH OGONEK
			"\xc4\x9a" => 'E', //U+011A	Ě 	LATIN CAPITAL LETTER E WITH CARON
			"\xc4\x9b" => 'e', //U+011B	ě 	LATIN SMALL LETTER E WITH CARON
			"\xc4\x9c" => 'G', //U+011C	Ĝ 	LATIN CAPITAL LETTER G WITH CIRCUMFLEX
			"\xc4\x9d" => 'g', //U+011D	ĝ 	LATIN SMALL LETTER G WITH CIRCUMFLEX
			"\xc4\x9e" => 'G', //U+011E	Ğ 	LATIN CAPITAL LETTER G WITH BREVE
			"\xc4\x9f" => 'g', //U+011F	ğ 	LATIN SMALL LETTER G WITH BREVE
			"\xc4\xa0" => 'G', //U+0120	Ġ 	LATIN CAPITAL LETTER G WITH DOT ABOVE
			"\xc4\xa1" => 'g', //U+0121	ġ 	LATIN SMALL LETTER G WITH DOT ABOVE
			"\xc4\xa2" => 'G', //U+0122	Ģ 	LATIN CAPITAL LETTER G WITH CEDILLA
			"\xc4\xa3" => 'g', //U+0123	ģ 	LATIN SMALL LETTER G WITH CEDILLA
			"\xc4\xa4" => 'H', //U+0124	Ĥ 	LATIN CAPITAL LETTER H WITH CIRCUMFLEX
			"\xc4\xa5" => 'h', //U+0125	ĥ 	LATIN SMALL LETTER H WITH CIRCUMFLEX
			"\xc4\xa6" => 'H', //U+0126	Ħ 	LATIN CAPITAL LETTER H WITH STROKE
			"\xc4\xa7" => 'h', //U+0127	ħ 	LATIN SMALL LETTER H WITH STROKE
			"\xc4\xa8" => 'I', //U+0128	Ĩ 	LATIN CAPITAL LETTER I WITH TILDE
			"\xc4\xa9" => 'i', //U+0129	ĩ 	LATIN SMALL LETTER I WITH TILDE
			"\xc4\xaa" => 'I', //U+012A	Ī 	LATIN CAPITAL LETTER I WITH MACRON
			"\xc4\xab" => 'i', //U+012B	ī 	LATIN SMALL LETTER I WITH MACRON
			"\xc4\xac" => 'I', //U+012C	Ĭ 	LATIN CAPITAL LETTER I WITH BREVE
			"\xc4\xad" => 'i', //U+012D	ĭ 	LATIN SMALL LETTER I WITH BREVE
			"\xc4\xae" => 'I', //U+012E	Į 	LATIN CAPITAL LETTER I WITH OGONEK
			"\xc4\xaf" => 'i', //U+012F	į 	LATIN SMALL LETTER I WITH OGONEK
			"\xc4\xb0" => 'I', //U+0130	İ 	LATIN CAPITAL LETTER I WITH DOT ABOVE
			"\xc4\xb1" => 'i', //U+0131	ı 	LATIN SMALL LETTER DOTLESS I
			"\xc4\xb2" => 'IJ', //U+0132	Ĳ 	LATIN CAPITAL LIGATURE IJ
			"\xc4\xb3" => 'ij', //U+0133	ĳ 	LATIN SMALL LIGATURE IJ
			"\xc4\xb4" => 'J', //U+0134	Ĵ 	LATIN CAPITAL LETTER J WITH CIRCUMFLEX
			"\xc4\xb5" => 'j', //U+0135	ĵ 	LATIN SMALL LETTER J WITH CIRCUMFLEX
			"\xc4\xb6" => 'K', //U+0136	Ķ 	LATIN CAPITAL LETTER K WITH CEDILLA
			"\xc4\xb7" => 'k', //U+0137	ķ 	LATIN SMALL LETTER K WITH CEDILLA
			"\xc4\xb8" => 'k', //U+0138	ĸ 	LATIN SMALL LETTER KRA
			"\xc4\xb9" => 'L', //U+0139	Ĺ 	LATIN CAPITAL LETTER L WITH ACUTE
			"\xc4\xba" => 'l', //U+013A	ĺ 	LATIN SMALL LETTER L WITH ACUTE
			"\xc4\xbb" => 'L', //U+013B	Ļ 	LATIN CAPITAL LETTER L WITH CEDILLA
			"\xc4\xbc" => 'l', //U+013C	ļ 	LATIN SMALL LETTER L WITH CEDILLA
			"\xc4\xbd" => 'L', //U+013D	Ľ 	LATIN CAPITAL LETTER L WITH CARON
			"\xc4\xbe" => 'l', //U+013E	ľ 	LATIN SMALL LETTER L WITH CARON
			"\xc4\xbf" => 'L', //U+013F	Ŀ 	LATIN CAPITAL LETTER L WITH MIDDLE DOT
			"\xc5\x80" => 'l', //U+0140	ŀ 	LATIN SMALL LETTER L WITH MIDDLE DOT
			"\xc5\x81" => 'L', //U+0141	Ł 	LATIN CAPITAL LETTER L WITH STROKE
			"\xc5\x82" => 'l', //U+0142	ł 	LATIN SMALL LETTER L WITH STROKE
			"\xc5\x83" => 'N', //U+0143	Ń 	LATIN CAPITAL LETTER N WITH ACUTE
			"\xc5\x84" => 'n', //U+0144	ń 	LATIN SMALL LETTER N WITH ACUTE
			"\xc5\x85" => 'N', //U+0145	Ņ 	LATIN CAPITAL LETTER N WITH CEDILLA
			"\xc5\x86" => 'n', //U+0146	ņ 	LATIN SMALL LETTER N WITH CEDILLA
			"\xc5\x87" => 'N', //U+0147	Ň 	LATIN CAPITAL LETTER N WITH CARON
			"\xc5\x88" => 'n', //U+0148	ň 	LATIN SMALL LETTER N WITH CARON
			"\xc5\x89" => '\'n', //U+0149	ŉ 	LATIN SMALL LETTER N PRECEDED BY APOSTROPHE
			"\xc5\x8a" => 'N', //U+014A	Ŋ 	LATIN CAPITAL LETTER ENG
			"\xc5\x8b" => 'n', //U+014B	ŋ 	LATIN SMALL LETTER ENG
			"\xc5\x8c" => 'O', //U+014C	Ō 	LATIN CAPITAL LETTER O WITH MACRON
			"\xc5\x8d" => 'o', //U+014D	ō 	LATIN SMALL LETTER O WITH MACRON
			"\xc5\x8e" => 'O', //U+014E	Ŏ 	LATIN CAPITAL LETTER O WITH BREVE
			"\xc5\x8f" => 'o', //U+014F	ŏ 	LATIN SMALL LETTER O WITH BREVE
			"\xc5\x90" => 'O', //U+0150	Ő 	LATIN CAPITAL LETTER O WITH DOUBLE ACUTE
			"\xc5\x91" => 'o', //U+0151	ő 	LATIN SMALL LETTER O WITH DOUBLE ACUTE
			"\xc5\x92" => 'OE', //U+0152	Œ 	LATIN CAPITAL LIGATURE OE
			"\xc5\x93" => 'oe', //U+0153	œ 	LATIN SMALL LIGATURE OE
			"\xc5\x94" => 'R', //U+0154	Ŕ 	LATIN CAPITAL LETTER R WITH ACUTE
			"\xc5\x95" => 'r', //U+0155	ŕ 	LATIN SMALL LETTER R WITH ACUTE
			"\xc5\x96" => 'R', //U+0156	Ŗ 	LATIN CAPITAL LETTER R WITH CEDILLA
			"\xc5\x97" => 'r', //U+0157	ŗ 	LATIN SMALL LETTER R WITH CEDILLA
			"\xc5\x98" => 'R', //U+0158	Ř 	LATIN CAPITAL LETTER R WITH CARON
			"\xc5\x99" => 'r', //U+0159	ř 	LATIN SMALL LETTER R WITH CARON
			"\xc5\x9a" => 'S', //U+015A	Ś 	LATIN CAPITAL LETTER S WITH ACUTE
			"\xc5\x9b" => 's', //U+015B	ś 	LATIN SMALL LETTER S WITH ACUTE
			"\xc5\x9c" => 'S', //U+015C	Ŝ 	LATIN CAPITAL LETTER S WITH CIRCUMFLEX
			"\xc5\x9d" => 's', //U+015D	ŝ 	LATIN SMALL LETTER S WITH CIRCUMFLEX
			"\xc5\x9e" => 'S', //U+015E	Ş 	LATIN CAPITAL LETTER S WITH CEDILLA
			"\xc5\x9f" => 's', //U+015F	ş 	LATIN SMALL LETTER S WITH CEDILLA
			"\xc5\xa0" => 'S', //U+0160	Š 	LATIN CAPITAL LETTER S WITH CARON
			"\xc5\xa1" => 's', //U+0161	š 	LATIN SMALL LETTER S WITH CARON
			"\xc5\xa2" => 'T', //U+0162	Ţ 	LATIN CAPITAL LETTER T WITH CEDILLA
			"\xc5\xa3" => 't', //U+0163	ţ 	LATIN SMALL LETTER T WITH CEDILLA
			"\xc5\xa4" => 'T', //U+0164	Ť 	LATIN CAPITAL LETTER T WITH CARON
			"\xc5\xa5" => 't', //U+0165	ť 	LATIN SMALL LETTER T WITH CARON
			"\xc5\xa6" => 'T', //U+0166	Ŧ 	LATIN CAPITAL LETTER T WITH STROKE
			"\xc5\xa7" => 't', //U+0167	ŧ 	LATIN SMALL LETTER T WITH STROKE
			"\xc5\xa8" => 'U', //U+0168	Ũ 	LATIN CAPITAL LETTER U WITH TILDE
			"\xc5\xa9" => 'u', //U+0169	ũ 	LATIN SMALL LETTER U WITH TILDE
			"\xc5\xaa" => 'U', //U+016A	Ū 	LATIN CAPITAL LETTER U WITH MACRON
			"\xc5\xab" => 'u', //U+016B	ū 	LATIN SMALL LETTER U WITH MACRON
			"\xc5\xac" => 'U', //U+016C	Ŭ 	LATIN CAPITAL LETTER U WITH BREVE
			"\xc5\xad" => 'u', //U+016D	ŭ 	LATIN SMALL LETTER U WITH BREVE
			"\xc5\xae" => 'U', //U+016E	Ů 	LATIN CAPITAL LETTER U WITH RING ABOVE
			"\xc5\xaf" => 'u', //U+016F	ů 	LATIN SMALL LETTER U WITH RING ABOVE
			"\xc5\xb0" => 'U', //U+0170	Ű 	LATIN CAPITAL LETTER U WITH DOUBLE ACUTE
			"\xc5\xb1" => 'u', //U+0171	ű 	LATIN SMALL LETTER U WITH DOUBLE ACUTE
			"\xc5\xb2" => 'U', //U+0172	Ų 	LATIN CAPITAL LETTER U WITH OGONEK
			"\xc5\xb3" => 'u', //U+0173	ų 	LATIN SMALL LETTER U WITH OGONEK
			"\xc5\xb4" => 'W', //U+0174	Ŵ 	LATIN CAPITAL LETTER W WITH CIRCUMFLEX
			"\xc5\xb5" => 'w', //U+0175	ŵ 	LATIN SMALL LETTER W WITH CIRCUMFLEX
			"\xc5\xb6" => 'Y', //U+0176	Ŷ 	LATIN CAPITAL LETTER Y WITH CIRCUMFLEX
			"\xc5\xb7" => 'y', //U+0177	ŷ 	LATIN SMALL LETTER Y WITH CIRCUMFLEX
			"\xc5\xb8" => 'Y', //U+0178	Ÿ 	LATIN CAPITAL LETTER Y WITH DIAERESIS
			"\xc5\xb9" => 'Z', //U+0179	Ź 	LATIN CAPITAL LETTER Z WITH ACUTE
			"\xc5\xba" => 'z', //U+017A	ź 	LATIN SMALL LETTER Z WITH ACUTE
			"\xc5\xbb" => 'Z', //U+017B	Ż 	LATIN CAPITAL LETTER Z WITH DOT ABOVE
			"\xc5\xbc" => 'z', //U+017C	ż 	LATIN SMALL LETTER Z WITH DOT ABOVE
			"\xc5\xbd" => 'Z', //U+017D	Ž 	LATIN CAPITAL LETTER Z WITH CARON
			"\xc5\xbe" => 'z', //U+017E	ž 	LATIN SMALL LETTER Z WITH CARON
			"\xc5\xbf" => 'S', //U+017F	ſ 	LATIN SMALL LETTER LONG S
			"\xc6\x80" => 'b', //U+0180	ƀ 	LATIN SMALL LETTER B WITH STROKE
			"\xc6\x81" => 'b', //U+0181	Ɓ 	LATIN CAPITAL LETTER B WITH HOOK
			"\xc6\x82" => 'b', //U+0182	Ƃ 	LATIN CAPITAL LETTER B WITH TOPBAR
			"\xc6\x83" => 'b', //U+0183	ƃ 	LATIN SMALL LETTER B WITH TOPBAR
			"\xc6\x84" => 'b', //U+0184	Ƅ 	LATIN CAPITAL LETTER TONE SIX
			"\xc6\x85" => 'b', //U+0185	ƅ 	LATIN SMALL LETTER TONE SIX
			// "\xc6\x86" => '', //U+0186	Ɔ 	LATIN CAPITAL LETTER OPEN O
			"\xc6\x87" => 'C', //U+0187	Ƈ 	LATIN CAPITAL LETTER C WITH HOOK
			"\xc6\x88" => 'c', //U+0188	ƈ 	LATIN SMALL LETTER C WITH HOOK
			"\xc6\x89" => 'D', //U+0189	Ɖ 	LATIN CAPITAL LETTER AFRICAN D
			"\xc6\x8a" => 'D', //U+018A	Ɗ 	LATIN CAPITAL LETTER D WITH HOOK
			"\xc6\x8b" => 'D', //U+018B	Ƌ 	LATIN CAPITAL LETTER D WITH TOPBAR
			"\xc6\x8c" => 'd', //U+018C	ƌ 	LATIN SMALL LETTER D WITH TOPBAR
			// "\xc6\x8d" => '', //U+018D	ƍ 	LATIN SMALL LETTER TURNED DELTA
			// "\xc6\x8e" => '', //U+018E	Ǝ 	LATIN CAPITAL LETTER REVERSED E
			// "\xc6\x8f" => '', //U+018F	Ə 	LATIN CAPITAL LETTER SCHWA
			// "\xc6\x90" => '', //U+0190	Ɛ 	LATIN CAPITAL LETTER OPEN E
			"\xc6\x91" => 'F', //U+0191	Ƒ 	LATIN CAPITAL LETTER F WITH HOOK
			"\xc6\x92" => 'f', //U+0192	ƒ 	LATIN SMALL LETTER F WITH HOOK
			"\xc6\x93" => 'G', //U+0193	Ɠ 	LATIN CAPITAL LETTER G WITH HOOK
			"\xc6\x94" => 'Y', //U+0194	Ɣ 	LATIN CAPITAL LETTER GAMMA
			"\xc6\x95" => 'hv', //U+0195	ƕ 	LATIN SMALL LETTER HV
			// "\xc6x96" => '', //U+0196	Ɩ 	LATIN CAPITAL LETTER IOTA
			// "\xc6x97" => '', //U+0197	Ɨ 	LATIN CAPITAL LETTER I WITH STROKE
			"\xc6\x98" => 'K', //U+0198	Ƙ 	LATIN CAPITAL LETTER K WITH HOOK
			"\xc6\x99" => 'k', //U+0199	ƙ 	LATIN SMALL LETTER K WITH HOOK
			"\xc6\x9a" => 'l', //U+019A	ƚ 	LATIN SMALL LETTER L WITH BAR
			// "\xc6x9b" => '', //U+019B	ƛ 	LATIN SMALL LETTER LAMBDA WITH STROKE
			"\xc6\x9c" => 'M', //U+019C	Ɯ 	LATIN CAPITAL LETTER TURNED M
			"\xc6\x9d" => 'N', //U+019D	Ɲ 	LATIN CAPITAL LETTER N WITH LEFT HOOK
			"\xc6\x9e" => 'n', //U+019E	ƞ 	LATIN SMALL LETTER N WITH LONG RIGHT LEG
			"\xc6\x9f" => 'O', //U+019F	Ɵ 	LATIN CAPITAL LETTER O WITH MIDDLE TILDE
			"\xc6\xa0" => 'O', //U+01A0	Ơ 	LATIN CAPITAL LETTER O WITH HORN
			"\xc6\xa1" => 'o', //U+01A1	ơ 	LATIN SMALL LETTER O WITH HORN
			"\xc6\xa2" => 'OI', //U+01A2	Ƣ 	LATIN CAPITAL LETTER OI
			"\xc6\xa3" => 'oi', //U+01A3	ƣ 	LATIN SMALL LETTER OI
			"\xc6\xa4" => 'P', //U+01A4	Ƥ 	LATIN CAPITAL LETTER P WITH HOOK
			"\xc6\xa5" => 'p', //U+01A5	ƥ 	LATIN SMALL LETTER P WITH HOOK
			"\xc6\xa6" => 'yr', //U+01A6	Ʀ 	LATIN LETTER YR
			// "\xc6\xa7" => '', //U+01A7	Ƨ 	LATIN CAPITAL LETTER TONE TWO
			// "\xc6\xa8" => '', //U+01A8	ƨ 	LATIN SMALL LETTER TONE TWO
			// "\xc6\xa9" => '', //U+01A9	Ʃ 	LATIN CAPITAL LETTER ESH
			// "\xc6\xaa" => '', //U+01AA	ƪ 	LATIN LETTER REVERSED ESH LOOP
// 			"\xc6\xab" => '', //U+01AB	ƫ 	LATIN SMALL LETTER T WITH PALATAL HOOK
// 			"\xc6\xac" => '', //U+01AC	Ƭ 	LATIN CAPITAL LETTER T WITH HOOK
// 			"\xc6\xad" => '', //U+01AD	ƭ 	LATIN SMALL LETTER T WITH HOOK
// 			"\xc6\xae" => '', //U+01AE	Ʈ 	LATIN CAPITAL LETTER T WITH RETROFLEX HOOK
// 			"\xc6\xaf" => '', //U+01AF	Ư 	LATIN CAPITAL LETTER U WITH HORN
// 			"\xc6\xb0" => '', //U+01B0	ư 	LATIN SMALL LETTER U WITH HORN
// 			"\xc6\xb1" => '', //U+01B1	Ʊ 	LATIN CAPITAL LETTER UPSILON
// 			"\xc6\xb2" => '', //U+01B2	Ʋ 	LATIN CAPITAL LETTER V WITH HOOK
// 			"\xc6\xb3" => '', //U+01B3	Ƴ 	LATIN CAPITAL LETTER Y WITH HOOK
// 			"\xc6\xb4" => '', //U+01B4	ƴ 	LATIN SMALL LETTER Y WITH HOOK
// 			"\xc6\xb5" => '', //U+01B5	Ƶ 	LATIN CAPITAL LETTER Z WITH STROKE
// 			"\xc6\xb6" => '', //U+01B6	ƶ 	LATIN SMALL LETTER Z WITH STROKE
// 			"\xc6\xb7" => '', //U+01B7	Ʒ 	LATIN CAPITAL LETTER EZH
// 			"\xc6\xb8" => '', //U+01B8	Ƹ 	LATIN CAPITAL LETTER EZH REVERSED
// 			"\xc6\xb9" => '', //U+01B9	ƹ 	LATIN SMALL LETTER EZH REVERSED
// 			"\xc6\xba" => '', //U+01BA	ƺ 	LATIN SMALL LETTER EZH WITH TAIL
// 			"\xc6\xbb" => '', //U+01BB	ƻ 	LATIN LETTER TWO WITH STROKE
// 			"\xc6\xbc" => '', //U+01BC	Ƽ 	LATIN CAPITAL LETTER TONE FIVE
// 			"\xc6\xbd" => '', //U+01BD	ƽ 	LATIN SMALL LETTER TONE FIVE
// 			"\xc6\xbe" => '', //U+01BE	ƾ 	LATIN LETTER INVERTED GLOTTAL STOP WITH STROKE
// 			"\xc6\xbf" => '', //U+01BF	ƿ 	LATIN LETTER WYNN
// 			"\xc7\x80" => '', //U+01C0	ǀ 	LATIN LETTER DENTAL CLICK
// 			"\xc7\x81" => '', //U+01C1	ǁ 	LATIN LETTER LATERAL CLICK
// 			"\xc7\x82" => '', //U+01C2	ǂ 	LATIN LETTER ALVEOLAR CLICK
// 			"\xc7\x83" => '', //U+01C3	ǃ 	LATIN LETTER RETROFLEX CLICK
			"\xc7\x84" => 'DZ', //U+01C4	Ǆ 	LATIN CAPITAL LETTER DZ WITH CARON
			"\xc7\x85" => 'Dz', //U+01C5	ǅ 	LATIN CAPITAL LETTER D WITH SMALL LETTER Z WITH CARON
			"\xc7\x86" => 'dz', //U+01C6	ǆ 	LATIN SMALL LETTER DZ WITH CARON
			"\xc7\x87" => 'LJ', //U+01C7	Ǉ 	LATIN CAPITAL LETTER LJ
			"\xc7\x88" => 'Lj', //U+01C8	ǈ 	LATIN CAPITAL LETTER L WITH SMALL LETTER J
			"\xc7\x89" => 'lj', //U+01C9	ǉ 	LATIN SMALL LETTER LJ
			"\xc7\x8a" => 'NJ', //U+01CA	Ǌ 	LATIN CAPITAL LETTER NJ
			"\xc7\x8b" => 'Nj', //U+01CB	ǋ 	LATIN CAPITAL LETTER N WITH SMALL LETTER J
			"\xc7\x8c" => 'nj', //U+01CC	ǌ 	LATIN SMALL LETTER NJ
			"\xc7\x8d" => 'A', //U+01CD	Ǎ 	LATIN CAPITAL LETTER A WITH CARON
			"\xc7\x8e" => 'a', //U+01CE	ǎ 	LATIN SMALL LETTER A WITH CARON
			"\xc7\x8f" => 'I', //U+01CF	Ǐ 	LATIN CAPITAL LETTER I WITH CARON
			"\xc7\x90" => 'i', //U+01D0	ǐ 	LATIN SMALL LETTER I WITH CARON
			"\xc7\x91" => 'O', //U+01D1	Ǒ 	LATIN CAPITAL LETTER O WITH CARON
			"\xc7\x92" => 'o', //U+01D2	ǒ 	LATIN SMALL LETTER O WITH CARON
			"\xc7\x93" => 'U', //U+01D3	Ǔ 	LATIN CAPITAL LETTER U WITH CARON
			"\xc7\x94" => 'u', //U+01D4	ǔ 	LATIN SMALL LETTER U WITH CARON
			"\xc7\x95" => 'U', //U+01D5	Ǖ 	LATIN CAPITAL LETTER U WITH DIAERESIS AND MACRON
			"\xc7\x96" => 'u', //U+01D6	ǖ 	LATIN SMALL LETTER U WITH DIAERESIS AND MACRON
			"\xc7\x97" => 'U', //U+01D7	Ǘ 	LATIN CAPITAL LETTER U WITH DIAERESIS AND ACUTE
			"\xc7\x98" => 'u', //U+01D8	ǘ 	LATIN SMALL LETTER U WITH DIAERESIS AND ACUTE
			"\xc7\x99" => 'U', //U+01D9	Ǚ 	LATIN CAPITAL LETTER U WITH DIAERESIS AND CARON
			"\xc7\x9a" => 'u', //U+01DA	ǚ 	LATIN SMALL LETTER U WITH DIAERESIS AND CARON
			"\xc7\x9b" => 'U', //U+01DB	Ǜ 	LATIN CAPITAL LETTER U WITH DIAERESIS AND GRAVE
			"\xc7\x9c" => 'u', //U+01DC	ǜ 	LATIN SMALL LETTER U WITH DIAERESIS AND GRAVE
			"\xc7\x9d" => 'a', //U+01DD	ǝ 	LATIN SMALL LETTER TURNED E
			"\xc7\x9e" => 'A', //U+01DE	Ǟ 	LATIN CAPITAL LETTER A WITH DIAERESIS AND MACRON
			"\xc7\x9f" => 'a', //U+01DF	ǟ 	LATIN SMALL LETTER A WITH DIAERESIS AND MACRON
			"\xc7\xa0" => 'A', //U+01E0	Ǡ 	LATIN CAPITAL LETTER A WITH DOT ABOVE AND MACRON
			"\xc7\xa1" => 'a', //U+01E1	ǡ 	LATIN SMALL LETTER A WITH DOT ABOVE AND MACRON
			"\xc7\xa2" => 'AE', //U+01E2	Ǣ 	LATIN CAPITAL LETTER AE WITH MACRON
			"\xc7\xa3" => 'ae', //U+01E3	ǣ 	LATIN SMALL LETTER AE WITH MACRON
			"\xc7\xa4" => 'G', //U+01E4	Ǥ 	LATIN CAPITAL LETTER G WITH STROKE
			"\xc7\xa5" => 'g', //U+01E5	ǥ 	LATIN SMALL LETTER G WITH STROKE
			"\xc7\xa6" => 'G', //U+01E6	Ǧ 	LATIN CAPITAL LETTER G WITH CARON
			"\xc7\xa7" => 'g', //U+01E7	ǧ 	LATIN SMALL LETTER G WITH CARON
			"\xc7\xa8" => 'K', //U+01E8	Ǩ 	LATIN CAPITAL LETTER K WITH CARON
			"\xc7\xa9" => 'k', //U+01E9	ǩ 	LATIN SMALL LETTER K WITH CARON
			"\xc7\xaa" => 'O', //U+01EA	Ǫ 	LATIN CAPITAL LETTER O WITH OGONEK
			"\xc7\xab" => 'o', //U+01EB	ǫ 	LATIN SMALL LETTER O WITH OGONEK
			"\xc7\xac" => 'O', //U+01EC	Ǭ 	LATIN CAPITAL LETTER O WITH OGONEK AND MACRON
			"\xc7\xad" => 'o', //U+01ED	ǭ 	LATIN SMALL LETTER O WITH OGONEK AND MACRON
			// "\xc7\xae" => '', //U+01EE	Ǯ 	LATIN CAPITAL LETTER EZH WITH CARON
			// "\xc7\xaf" => '', //U+01EF	ǯ 	LATIN SMALL LETTER EZH WITH CARON
			"\xc7\xb0" => 'J', //U+01F0	ǰ 	LATIN SMALL LETTER J WITH CARON
			"\xc7\xb1" => 'DZ', //U+01F1	Ǳ 	LATIN CAPITAL LETTER DZ
			"\xc7\xb2" => 'Dz', //U+01F2	ǲ 	LATIN CAPITAL LETTER D WITH SMALL LETTER Z
			"\xc7\xb3" => 'dz', //U+01F3	ǳ 	LATIN SMALL LETTER DZ
			"\xc7\xb4" => 'G', //U+01F4	Ǵ 	LATIN CAPITAL LETTER G WITH ACUTE
			"\xc7\xb5" => 'g', //U+01F5	ǵ 	LATIN SMALL LETTER G WITH ACUTE
			// "\xc7\xb6" => '', //U+01F6	Ƕ 	LATIN CAPITAL LETTER HWAIR
			// "\xc7\xb7" => '', //U+01F7	Ƿ 	LATIN CAPITAL LETTER WYNN
			"\xc7\xb8" => 'N', //U+01F8	Ǹ 	LATIN CAPITAL LETTER N WITH GRAVE
			"\xc7\xb9" => 'n', //U+01F9	ǹ 	LATIN SMALL LETTER N WITH GRAVE
			"\xc7\xba" => 'A', //U+01FA	Ǻ 	LATIN CAPITAL LETTER A WITH RING ABOVE AND ACUTE
			"\xc7\xbb" => 'a', //U+01FB	ǻ 	LATIN SMALL LETTER A WITH RING ABOVE AND ACUTE
			"\xc7\xbc" => 'Ae', //U+01FC	Ǽ 	LATIN CAPITAL LETTER AE WITH ACUTE
			"\xc7\xbd" => 'ae', //U+01FD	ǽ 	LATIN SMALL LETTER AE WITH ACUTE
			"\xc7\xbe" => 'O', //U+01FE	Ǿ 	LATIN CAPITAL LETTER O WITH STROKE AND ACUTE
			"\xc7\xbf" => 'o', //U+01FF	ǿ 	LATIN SMALL LETTER O WITH STROKE AND ACUTE
			
			"\xc9\x99" => "2", // LATIN SMALL LETTER SCHWA
			
			"\xCF\x9F" => "", // GREEK SMALL LETTER KOPPA

					
			"\xE2\x82\xAC" /* '€' */ => "euro", // Unicode Character 'EURO SIGN' (U+20AC)
			"\xE2\x84\xA2" => "(tm)", // Trade mark
			"\xE2\x96\xBA" => "-", // A ">" character..
			"\xE2\x97\x86" => "",
			"\xE2\x98\x86" => "", // WHITE STAR
			"\xE2\x98\x85" => "", // BLACK STAR
			'…' => "...",
			'”' => "-",
			'"' => "-",
			'&' => 'et',
			'’' => '\'',
			'‘' => '\'',
			'´' => '\'',
			'–' => '-'
	];
	
	
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
			$value = (string)$_REQUEST[$name];
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

	/**
	 * Remove the capital on the first character of the string.
	 *
	 * @param string $text the string to capitalize. If
	 * 	NULL is passed, the returned string is an empty one.
	 * @return string the string uncapitalized.
	 *
	 */
	public static function uncapitalizeFirst($text){
		if( !$text ) return "";
		$text = trim( $text );
		if( strlen($text) < 1 ) return "";
		return std::lower(mb_substr($text,0,1,'UTF-8')) . mb_substr($text,1,NULL,'UTF-8');
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
	 * Clean of the text for using it as part of an URL. Based
	 * on rules about encoding URLs. This function is intended
	 * to maximize the results given by the search engines.
	 *	Experience shows that using text in the URL itself
	 *	gives better ranking on Google and other search engines.
	 *
	 * NOTE: some changes can occurs on translation before the
	 * version 1 of the library. Even, the system is quite stable,
	 * we can decide some changes.
	 *
	 * @param string $str the text to encode.
	 * @param int $max_len the maximum number of characters
	 *		to return when the text is cleaned.
	 * @param int $converter The array for converting. Uses
	 * 		the basic converter provided with the library.
	 * @return string an encoded text without punctuation.
	 * 		Some accents and UTF8 characters are kept
	 * 		for a better view on search engines.
	 */
	public static function url_clean($str, $max_len = 72, $converter = self::BASIC_CONVERTER ) {
	
		// Limit to 70 characters
		$truncated = '';
		$str = std::lower($str);
	
		// Net line will create an array of chars (UTF-8)A
		// see http://stackoverflow.com/questions/3666306/how-to-iterate-utf-8-string-in-php
		$char_array = preg_split('//u', $str, -1, PREG_SPLIT_NO_EMPTY);
		$ret = '';
		foreach( $char_array as $c ){
			if( ($c >= 'a' && $c <= 'z') || ($c >= '0' && $c <= '9') || in_array( $c, [ '(', ')', '$', '_', '\'' ] )  /* can be also: '$-_.+!*\'(), */  ){
				$ret .= $c;
			}
			else if( isset( $converter[$c] ) ){
				$ret .= $converter[$c];
			}
			else if( strlen($c) > 1 ){
				// UTF-8
				$ret .= urlencode($c);
			}
			else if( std::lastChar($ret) != '-' ){
				if( std::len($ret) <= $max_len ) $truncated = $ret;
				$ret .= '-';
			}
		}
	
		// Use no more than 72 characters
		if( std::len($ret) <= $max_len ){
			// Add the last word when necessary
			$truncated = $ret;
		}
		$ret = trim( $truncated, '-' );
		if( !$ret ) $ret = 'empty'; // avoid empty data
		return $ret;
	}
	
	
	/**
	 * Clean of the text for real text output. Based
	 * on rules about encoding URLs. This function is intended
	 * to maximize the results given by the search engines.
	 *
	 * @deprecated use url_clean() instead
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
	 * Try to add an ellipsis to the text. The ellipsis used is the UNICODE
	 * character (rather than 3 dots).
	 * 
	 * You can NOT create an ellipsis for a text less than 10 characters
	 * (in UNICODE characters, not UTF-8 counting).
	 * 
	 * @param string $text the text to ellipsis
	 * @param int $max_length the maximum length you accept.
	 * @param boolean $word_cut if the cut must be done at a word rather than
	 *   anywhere in the text. This flag is only indeicative.
	 * 
	 */
	public static function ellipsis($text, $max_length, $word_cut = TRUE)
	{
		if( $max_length < 10 ) $max_length = 10; 
		if( std::len($text) > $max_length ){
			$text = mb_substr($text, 0, $max_length - 5, 'utf-8');
			if( $word_cut && $max_length > 30 ){
				// Try to cut at a space...
				$i = $len = std::len($text);
				while( $i > 20 && $i > $len - 30) {
					if( ctype_space( $text[--$i] ) ){
						// DO NOT USE mb_substr as we found the cut based
						// on the byte inside the string (and not the caracter).
						// This is faster, then don't worry.
						$text = substr($text, 0, $i+1);
						$i = 0; // end the loop 
					}
				}
			}
			$text = trim($text);
			$text .= "\u{2026}"; // Ellipsis as unicode
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
	
	/**
	 * This text suppress multiple sucessive blank characters.
	 * 
	 * @param string $text a text (could be HTML but not a script).
	 *  @return string the text with only 1 white space between words. 
	 */
	public static function compressText( $text ){
		if( !$text ) return "";
		return preg_replace('/\s+/', ' ', $text);
	}
	
	
	/**
	 * Get the timezone offset.
	 * 
	 * @param string $tz the name of the timezone. If not given,
	 * 		we use the current timezone.
	 * @return int the number of seconds for the offset.
	 * 
	 */
	public static function getTimezoneOffset( $tz = null ){
		if( !$tz ){
			$tz = date_default_timezone_get();
		}
		$datetime_tz = new \DateTimeZone( $tz );
		$offset = $datetime_tz->getOffset( new \DateTime() );
		return $offset;
	}
}


