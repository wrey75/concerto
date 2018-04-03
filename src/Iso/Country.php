<?php 

namespace Concerto\Iso;


use Concerto\std;

class Country {
	public static $COUNTRIES = [
		 'AF' => [ 'label' => [ 'en' => 'Afghanistan' ], 'iso3' => 'AFG', 'num' => '004' ],
		 'AX' => [ 'label' => [ 'en' => 'Aland Islands' ], 'iso3' => 'ALA', 'num' => '248' ],
		 'AL' => [ 'label' => [ 'en' => 'Albania' ], 'iso3' => 'ALB', 'num' => '008' ],
		 'DZ' => [ 'label' => [ 'en' => 'Algeria' ], 'iso3' => 'DZA', 'num' => '012' ],
		 'AS' => [ 'label' => [ 'en' => 'American Samoa' ], 'iso3' => 'ASM', 'num' => '016' ],
		 'AD' => [ 'label' => [ 'en' => 'Andorra' ], 'iso3' => 'AND', 'num' => '020' ],
		 'AO' => [ 'label' => [ 'en' => 'Angola' ], 'iso3' => 'AGO', 'num' => '024' ],
		 'AI' => [ 'label' => [ 'en' => 'Anguilla' ], 'iso3' => 'AIA', 'num' => '660' ],
		 'AQ' => [ 'label' => [ 'en' => 'Antarctica' ], 'iso3' => 'ATA', 'num' => '010' ],
		 'AG' => [ 'label' => [ 'en' => 'Antigua' ], 'iso3' => 'ATG', 'num' => '028' ],
		 'AR' => [ 'label' => [ 'en' => 'Argentina' ], 'iso3' => 'ARG', 'num' => '032' ],
		 'AM' => [ 'label' => [ 
		 		'en' => 'Armenia',
		 		'fr' => 'Arménie' ], 'iso3' => 'ARM', 'num' => '051' ],
		 'AW' => [ 'label' => [ 'en' => 'Aruba' ], 'iso3' => 'ABW', 'num' => '533' ],
		 'AU' => [ 'label' => [ 'en' => 'Australia', 'fr'=>'Autriche' ], 'iso3' => 'AUS', 'num' => '036' ],
		 'AT' => [ 'label' => [ 
		 		'en' => 'Austria',
		 		'fr' => 'Autriche' ], 'iso3' => 'AUT', 'num' => '040' ],
		 'AZ' => [ 'label' => [ 'en' => 'Azerbaijan' ], 'iso3' => 'AZE', 'num' => '031' ],
		 'BS' => [ 'label' => [ 
		 		'en' => 'The Bahamas',
		 		'fr' => 'Bahamas'], 'iso3' => 'BHS', 'num' => '044' ],
		 'BH' => [ 'label' => [ 'en' => 'Bahrain' ], 'iso3' => 'BHR', 'num' => '048' ],
		 'BD' => [ 'label' => [ 'en' => 'Bangladesh' ], 'iso3' => 'BGD', 'num' => '050' ],
		 'BB' => [ 'label' => [ 'en' => 'Barbados' ], 'iso3' => 'BRB', 'num' => '052' ],
		 'BY' => [ 'label' => [ 'en' => 'Belarus' ], 'iso3' => 'BLR', 'num' => '112' ],
		 'BE' => [ 'label' => [
		 		'en' => 'Belgium', 
		 		'fr' => 'Belgique' ], 'iso3' => 'BEL', 'num' => 056 ],
		 'BZ' => [ 'label' => [ 'en' => 'Belize' ], 'iso3' => 'BLZ', 'num' => '084' ],
		 'BJ' => [ 'label' => [ 'en' => 'Benin' ], 'iso3' => 'BEN', 'num' => '204' ],
		 'BM' => [ 'label' => [ 'en' => 'Bermuda' ], 'iso3' => 'BMU', 'num' => '060' ],
		 'BT' => [ 'label' => [ 'en' => 'Bhutan' ], 'iso3' => 'BTN', 'num' => '064' ],
		 'BO' => [ 'label' => [ 'en' => 'Bolivia' ], 'iso3' => 'BOL', 'num' => '068' ],
		 'BA' => [ 'label' => [ 'en' => 'Bosnia and Herzegovina' ], 'iso3' => 'BIH', 'num' => '070' ],
		 'BW' => [ 'label' => [ 'en' => 'Botswana' ], 'iso3' => 'BWA', 'num' => '072' ],
		 'BV' => [ 'label' => [ 'en' => 'Bouvet Island' ], 'iso3' => 'BVT', 'num' => '074' ],
		 'BR' => [ 'label' => [ 'en' => 'Brazil' ], 'iso3' => 'BRA', 'num' => '076' ],
		 'VG' => [ 'label' => [ 'en' => 'British Virgin Islands' ], 'iso3' => 'VGB', 'num' => '092' ],
		 'IO' => [ 'label' => [ 'en' => 'British Indian Ocean Territory' ], 'iso3' => 'IOT', 'num' => '086' ],
		 'BN' => [ 'label' => [ 'en' => 'Brunei Darussalam' ], 'iso3' => 'BRN', 'num' => '096' ],
		 'BG' => [ 'label' => [ 
		 		'en' => 'Bulgaria',
		 		'fr' => 'Bulgarie' ], 'iso3' => 'BGR', 'num' => '100' ],
		 'BF' => [ 'label' => [ 'en' => 'Burkina Faso' ], 'iso3' => 'BFA', 'num' => '854' ],
		 'BI' => [ 'label' => [ 'en' => 'Burundi' ], 'iso3' => 'BDI', 'num' => '108' ],
		 'KH' => [ 'label' => [ 'en' => 'Cambodia' ], 'iso3' => 'KHM', 'num' => '116' ],
		 'CM' => [ 'label' => [ 'en' => 'Cameroon' ], 'iso3' => 'CMR', 'num' => '120' ],
		 'CA' => [ 'label' => [ 'en' => 'Canada' ], 'iso3' => 'CAN', 'num' => '124' ],
		 'CV' => [ 'label' => [ 'en' => 'Cape Verde' ], 'iso3' => 'CPV', 'num' => '132' ],
		 'KY' => [ 'label' => [ 'en' => 'Cayman Islands' ], 'iso3' => 'CYM', 'num' => '136' ],
		 'CF' => [ 'label' => [ 'en' => 'Central African Republic' ], 'iso3' => 'CAF', 'num' => '140' ],
		 'TD' => [ 'label' => [ 'en' => 'Chad' ], 'iso3' => 'TCD', 'num' => '148' ],
		 'CL' => [ 'label' => [ 'en' => 'Chile' ], 'iso3' => 'CHL', 'num' => '152' ],
		 'CN' => [ 'label' => [ 'en' => 'China' ], 'iso3' => 'CHN', 'num' => '156' ],
		 'HK' => [ 'label' => [ 
		 		'en' => 'Hong Kong',
		 		'fr' => 'Hong Kong',
		 		'ja' => '香港' ], 'iso3' => 'HKG', 'num' => '344' ],
		 'MO' => [ 'label' => [ 
		 		'en' => 'Macau',
		 		'fr' => 'Macao',
		 		'ja' => 'マカオ' ], 'iso3' => 'MAC', 'num' => '446' ],
		 'CX' => [ 'label' => [ 'en' => 'Christmas Island' ], 'iso3' => 'CXR', 'num' => '162' ],
		 'CC' => [ 'label' => [ 'en' => 'Cocos (Keeling) Islands' ], 'iso3' => 'CCK', 'num' => '166' ],
		 'CO' => [ 'label' => [ 'en' => 'Colombia' ], 'iso3' => 'COL', 'num' => '170' ],
		 'KM' => [ 'label' => [ 'en' => 'Comoros' ], 'iso3' => 'COM', 'num' => '174' ],
		 'CG' => [ 'label' => [ 
		 		'en' => 'Republic of the Congo',
		 		'fr' => 'République du Congo' ], 'iso3' => 'COG', 'num' => '178' ],
		 'CD' => [ 'label' => [ 'en' => 'Congo, Democratic Republic of the' ], 'iso3' => 'COD', 'num' => '180' ],
		 'CK' => [ 'label' => [ 'en' => 'Cook Islands' ], 'iso3' => 'COK', 'num' => '184' ],
		 'CR' => [ 'label' => [ 'en' => 'Costa Rica' ], 'iso3' => 'CRI', 'num' => '188' ],
		 'CI' => [ 'label' => [ 'en' => 'Côte d\'Ivoire' ], 'iso3' => 'CIV', 'num' => '384' ],
		 'HR' => [ 'label' => [ 'en' => 'Croatia' ], 'iso3' => 'HRV', 'num' => '191' ],
		 'CU' => [ 'label' => [ 'en' => 'Cuba' ], 'iso3' => 'CUB', 'num' => '192' ],
		 'CW' => [ 'label' => [
					'en' => 'Curaçao',
					'fr' => 'Curaçao' ], 'iso3' => 'CUW', 'num' => '531' ],
		 'CY' => [ 'label' => [ 
		 		'en' => 'Cyprus',
		 		'fr' => 'Chypre' ], 'iso3' => 'CYP', 'num' => '196' ],
		 'CZ' => [ 'label' => [ 
		 		'en' => 'Czech Republic',
		 		'fr' => 'République tchèque' ], 'iso3' => 'CZE', 'num' => '203' ],
		 'DK' => [ 'label' => [ 
		 		'en' => 'Denmark',
		 		'fr' => 'Danemark' ], 'iso3' => 'DNK', 'num' => '208' ],
		 'DJ' => [ 'label' => [ 'en' => 'Djibouti' ], 'iso3' => 'DJI', 'num' => '262' ],
		 'DM' => [ 'label' => [ 'en' => 'Dominica' ], 'iso3' => 'DMA', 'num' => '212' ],
		 'DO' => [ 'label' => [ 
		 		'en' => 'Dominican Republic',
		 		'fr' => 'République dominicaine'], 'iso3' => 'DOM', 'num' => '214' ],
		 'EC' => [ 'label' => [ 
		 		'en' => 'Ecuador',
		 		'fr' => 'Équateur' ], 'iso3' => 'ECU', 'num' => '218' ],
		 'EG' => [ 'label' => [ 
		 		'en' => 'Egypt',
		 		'fr' => 'Égypte'], 'iso3' => 'EGY', 'num' => '818' ],
		 'SV' => [ 'label' => [ 'en' => 'El Salvador' ], 'iso3' => 'SLV', 'num' => '222' ],
		 'GQ' => [ 'label' => [ 'en' => 'Equatorial Guinea' ], 'iso3' => 'GNQ', 'num' => '226' ],
		 'ER' => [ 'label' => [ 'en' => 'Eritrea' ], 'iso3' => 'ERI', 'num' => '232' ],
		 'EE' => [ 'label' => [ 'en' => 'Estonia' ], 'iso3' => 'EST', 'num' => '233' ],
		 'ET' => [ 'label' => [ 'en' => 'Ethiopia' ], 'iso3' => 'ETH', 'num' => '231' ],
		 'FK' => [ 'label' => [ 'en' => 'Falkland Islands (Malvinas)' ], 'iso3' => 'FLK', 'num' => '238' ],
		 'FO' => [ 'label' => [ 'en' => 'Faroe Islands' ], 'iso3' => 'FRO', 'num' => '234' ],
		 'FJ' => [ 'label' => [ 'en' => 'Fiji' ], 'iso3' => 'FJI', 'num' => '242' ],
		 'FI' => [ 'label' => [ 
		 		'en' => 'Finland',
		 		'fr' => 'Finlande'], 'iso3' => 'FIN', 'num' => '246' ],
		 'FR' => [ 'label' => [
		 		'de' => 'Frankreich',
		 		'en' => 'France',
		 		'fr' => 'France',
		 		'ja' => 'フランス' ], 'iso3' => 'FRA', 'num' => '250' ],
		 'GF' => [ 'label' => [ 'en' => 'French Guiana', 'fr'=>'Guyane' ], 'iso3' => 'GUF', 'num' => '254' ],
		 'PF' => [ 'label' => [ 'en' => 'French Polynesia' ], 'iso3' => 'PYF', 'num' => '258' ],
		 'TF' => [ 'label' => [ 'en' => 'French Southern Territories' ], 'iso3' => 'ATF', 'num' => '260' ],
		 'GA' => [ 'label' => [ 'en' => 'Gabon' ], 'iso3' => 'GAB', 'num' => '266' ],
		 'GM' => [ 'label' => [ 
		 		'en' => 'The Gambia',
		 		'fr' => 'Gambie' ], 'iso3' => 'GMB', 'num' => '270' ],
		 'GE' => [ 'label' => [ 'en' => 'Georgia' ], 'iso3' => 'GEO', 'num' => '268' ],
		 'DE' => [ 'label' => [ 
		 		'en' => 'Germany',
		 		'fr' => 'Allemagne' ], 'iso3' => 'DEU', 'num' => '276' ],
		 'GH' => [ 'label' => [ 'en' => 'Ghana' ], 'iso3' => 'GHA', 'num' => '288' ],
		 'GI' => [ 'label' => [ 'en' => 'Gibraltar' ], 'iso3' => 'GIB', 'num' => '292' ],
		 'GR' => [ 'label' => [ 'en' => 'Greece' ], 'iso3' => 'GRC', 'num' => '300' ],
		 'GL' => [ 'label' => [ 'en' => 'Greenland' ], 'iso3' => 'GRL', 'num' => '304' ],
		 'GD' => [ 'label' => [ 'en' => 'Grenada' ], 'iso3' => 'GRD', 'num' => '308' ],
		 'GP' => [ 'label' => [ 'en' => 'Guadeloupe' ], 'iso3' => 'GLP', 'num' => '312' ],
		 'GU' => [ 'label' => [ 'en' => 'Guam' ], 'iso3' => 'GUM', 'num' => '316' ],
		 'GT' => [ 'label' => [ 'en' => 'Guatemala' ], 'iso3' => 'GTM', 'num' => '320' ],
		 'GG' => [ 'label' => [ 'en' => 'Guernsey' ], 'iso3' => 'GGY', 'num' => '831' ],
		 'GN' => [ 'label' => [ 'en' => 'Guinea' ], 'iso3' => 'GIN', 'num' => '324' ],
		 'GW' => [ 'label' => [ 'en' => 'Guinea-Bissau' ], 'iso3' => 'GNB', 'num' => '624' ],
		 'GY' => [ 'label' => [ 'en' => 'Guyana' ], 'iso3' => 'GUY', 'num' => '328' ],
		 'HT' => [ 'label' => [ 'en' => 'Haiti' ], 'iso3' => 'HTI', 'num' => '332' ],
		 'HM' => [ 'label' => [ 'en' => 'Heard Island and Mcdonald Islands' ], 'iso3' => 'HMD', 'num' => '334' ],
		 'VA' => [ 'label' => [ 'en' => 'Holy See (Vatican City State)' ], 'iso3' => 'VAT', 'num' => '336' ],
		 'HN' => [ 'label' => [ 'en' => 'Honduras' ], 'iso3' => 'HND', 'num' => '340' ],
		 'HU' => [ 'label' => [ 
		 		'en' => 'Hungary',
		 		'fr' => 'Hongrie' ], 'iso3' => 'HUN', 'num' => '348' ],
		 'IS' => [ 'label' => [ 'en' => 'Iceland' ], 'iso3' => 'ISL', 'num' => '352' ],
		 'IN' => [ 'label' => [ 
		 		'en' => 'India',
		 		'fr' => 'Inde' ], 'iso3' => 'IND', 'num' => '356' ],
		 'ID' => [ 'label' => [ 
		 		'en' => 'Indonesia',
		 		'fr' => 'Indonésie' ], 'iso3' => 'IDN', 'num' => '360' ],
		 'IR' => [ 'label' => [ 
		 		'en' => 'Iran',
		 		'fr' => 'Iran' ], 'iso3' => 'IRN', 'num' => '364' ],
		 'IQ' => [ 'label' => [ 
		 		'en' => 'Iraq',
		 		'fr' => 'Irak' ], 'iso3' => 'IRQ', 'num' => '368' ],
		 'IE' => [ 'label' => [ 
		 		'en' => 'Ireland',
		 		'fr' => 'Irlande' ], 'iso3' => 'IRL', 'num' => '372' ],
		 'IM' => [ 'label' => [ 'en' => 'Isle of Man' ], 'iso3' => 'IMN', 'num' => '833' ],
		 'IL' => [ 'label' => [ 
		 		'en' => 'Israel', 
		 		'fr' => 'Israël' ], 'iso3' => 'ISR', 'num' => '376' ],
		 'IT' => [ 'label' => [ 
		 		'en' => 'Italy',
		 		'fr' => 'Italie' ], 'iso3' => 'ITA', 'num' => '380' ],
		 'JM' => [ 'label' => [ 
		 		'en' => 'Jamaica',
		 		'fr' => 'Jamaïque' ], 'iso3' => 'JAM', 'num' => '388' ],
		 'JP' => [ 'label' => [
		 		'en' => 'Japan', 
		 		'fr' => 'Japon' ], 'iso3' => 'JPN', 'num' => '392' ],
		 'JE' => [ 'label' => [ 'en' => 'Jersey' ], 'iso3' => 'JEY', 'num' => '832' ],
		 'JO' => [ 'label' => [ 
		 		'en' => 'Jordan',
		 		'fr' => 'Jordanie' ], 'iso3' => 'JOR', 'num' => '400' ],
		 'KZ' => [ 'label' => [ 'en' => 'Kazakhstan' ], 'iso3' => 'KAZ', 'num' => '398' ],
		 'KE' => [ 'label' => [ 'en' => 'Kenya' ], 'iso3' => 'KEN', 'num' => '404' ],
		 'KI' => [ 'label' => [ 'en' => 'Kiribati' ], 'iso3' => 'KIR', 'num' => '296' ],
		 'KP' => [ 'label' => [ 
		          'en' => 'North Korea',
		          'fr' => "Corée du Nord"], 'iso3' => 'PRK', 'num' => '408' ],
		 'KR' => [ 'label' => [ 
		 		'en' => 'South Korea',
		 		'fr' => 'Corée du Sud' ], 'iso3' => 'KOR', 'num' => '410' ],
		 'KW' => [ 'label' => [ 
                'en' => 'Kuwait',
		          'fr' => 'Koweït' ], 'iso3' => 'KWT', 'num' => '414' ],
		 'KG' => [ 'label' => [ 
		          'en' => 'Kyrgyzstan',
		          'fr' => 'Kirghizistan' ], 'iso3' => 'KGZ', 'num' => '417' ],
		 'LA' => [ 'label' => [ 
		 		'en' => 'Laos',
		 		'fr' => 'Laos' ], 'iso3' => 'LAO', 'num' => '418' ],
		 'LV' => [ 'label' => [ 
		 		'en' => 'Latvia',
		 		'fr' => 'Lettonie' ], 'iso3' => 'LVA', 'num' => '428' ],
		 'LB' => [ 'label' => [ 
		 		'en' => 'Lebanon',
		 		'fr' => 'Liban' ], 'iso3' => 'LBN', 'num' => '422' ],
		 'LS' => [ 'label' => [ 'en' => 'Lesotho' ], 'iso3' => 'LSO', 'num' => '426' ],
		 'LR' => [ 'label' => [ 'en' => 'Liberia' ], 'iso3' => 'LBR', 'num' => '430' ],
		 'LY' => [ 'label' => [ 
		          'en' => 'Libya',
		          'fr' => 'Libye'], 'iso3' => 'LBY', 'num' => '434' ],
		 'LI' => [ 'label' => [ 'en' => 'Liechtenstein' ], 'iso3' => 'LIE', 'num' => '438' ],
		 'LT' => [ 'label' => [ 
		 		'en' => 'Lithuania',
		 		'fr' => 'Lituanie',
		 		'es' => 'Lituania' ], 'iso3' => 'LTU', 'num' => '440' ],
		 'LU' => [ 'label' => [
		 		'en' => 'Luxembourg',
		 		'fr' => 'Luxembourg' ], 'iso3' => 'LUX', 'num' => '442' ],
		 'MK' => [ 'label' => [ 
		 		'en' => 'Macedonia',
		 		'fr' => 'Macédoine' ], 'iso3' => 'MKD', 'num' => '807' ],
		 'MG' => [ 'label' => [ 'en' => 'Madagascar' ], 'iso3' => 'MDG', 'num' => '450' ],
		 'MW' => [ 'label' => [ 'en' => 'Malawi' ], 'iso3' => 'MWI', 'num' => '454' ],
		 'MY' => [ 'label' => [ 
		          'en' => 'Malaysia',
		          'fr' => 'Malaisie' ], 'iso3' => 'MYS', 'num' => '458' ],
		 'MV' => [ 'label' => [ 'en' => 'Maldives' ], 'iso3' => 'MDV', 'num' => '462' ],
		 'ML' => [ 'label' => [ 'en' => 'Mali' ], 'iso3' => 'MLI', 'num' => '466' ],
		 'MT' => [ 'label' => [ 
		 		'en' => 'Malta',
		 		'fr' => 'Malte' ], 'iso3' => 'MLT', 'num' => '470' ],
		 'MH' => [ 'label' => [ 'en' => 'Marshall Islands' ], 'iso3' => 'MHL', 'num' => '584' ],
		 'MQ' => [ 'label' => [ 'en' => 'Martinique' ], 'iso3' => 'MTQ', 'num' => '474' ],
		 'MR' => [ 'label' => [ 
		          'en' => 'Mauritania',
		          'fr' => 'Mauritanie' ], 'iso3' => 'MRT', 'num' => '478' ],
		 'MU' => [ 'label' => [ 
		 		'en' => 'Mauritius',
		 		'fr' => 'Île Maurice'], 'iso3' => 'MUS', 'num' => '480' ],
		 'YT' => [ 'label' => [ 'en' => 'Mayotte' ], 'iso3' => 'MYT', 'num' => '175' ],
		 'MX' => [ 'label' => [ 
		 		'en' => 'Mexico',
		 		'fr' => 'Mexique' ], 'iso3' => 'MEX', 'num' => '484' ],
		 'FM' => [ 'label' => [
		 		'en' => 'Federated States of Micronesia',
		 		'fr' => 'Micronésie'], 'iso3' => 'FSM', 'num' => '583' ],
		 'MD' => [ 'label' => [ 
		 		'en' => 'Moldova',
		 		'fr' => 'Moldavie' ], 'iso3' => 'MDA', 'num' => '498' ],
		 'MC' => [ 'label' => [ 'en' => 'Monaco' ], 'iso3' => 'MCO', 'num' => '492' ],
		 'MN' => [ 'label' => [ 
		 		'en' => 'Mongolia',
		 		'fr' => 'Mongolie '], 'iso3' => 'MNG', 'num' => '496' ],
		 'ME' => [ 'label' => [ 'en' => 'Montenegro' ], 'iso3' => 'MNE', 'num' => '499' ],
		 'MS' => [ 'label' => [ 'en' => 'Montserrat' ], 'iso3' => 'MSR', 'num' => '500' ],
		 'MA' => [ 'label' => [ 
		 		'en' => 'Morocco',
		 		'fr' => 'Maroc' ], 'iso3' => 'MAR', 'num' => '504' ],
		 'MZ' => [ 'label' => [ 'en' => 'Mozambique' ], 'iso3' => 'MOZ', 'num' => '508' ],
		 'MM' => [ 'label' => [ 'en' => 'Myanmar' ], 'iso3' => 'MMR', 'num' => '104' ],
		 'NA' => [ 'label' => [ 
		          'en' => 'Namibia',
		          'fr' => 'Namibie' ], 'iso3' => 'NAM', 'num' => '516' ],
		 'NR' => [ 'label' => [ 'en' => 'Nauru' ], 'iso3' => 'NRU', 'num' => '520' ],
		 'NP' => [ 'label' => [ 'en' => 'Nepal' ], 'iso3' => 'NPL', 'num' => '524' ],
		 'NL' => [ 'label' => [ 
		 		'en' => 'Netherlands',
		 		'fr' => 'Pays-Bas' ], 'iso3' => 'NLD', 'num' => '528' ],
		 'AN' => [ 'label' => [ 'en' => 'Netherlands Antilles' ], 'iso3' => 'ANT', 'num' => '530' ],
		 'NC' => [ 'label' => [ 
		          'en' => 'New Caledonia',
		          'fr' => 'Nouvelle Calédonie'], 'iso3' => 'NCL', 'num' => '540' ],
		 'NZ' => [ 'label' => [ 
		 		'en' => 'New Zealand',
		 		'fr' => 'Nouvelle-Zélande' ], 'iso3' => 'NZL', 'num' => '554' ],
		 'NI' => [ 'label' => [ 'en' => 'Nicaragua' ], 'iso3' => 'NIC', 'num' => '558' ],
		 'NE' => [ 'label' => [ 'en' => 'Niger' ], 'iso3' => 'NER', 'num' => '562' ],
		 'NG' => [ 'label' => [ 'en' => 'Nigeria' ], 'iso3' => 'NGA', 'num' => '566' ],
		 'NU' => [ 'label' => [ 'en' => 'Niue' ], 'iso3' => 'NIU', 'num' => '570' ],
		 'NF' => [ 'label' => [ 'en' => 'Norfolk Island' ], 'iso3' => 'NFK', 'num' => '574' ],
		 'MP' => [ 'label' => [ 'en' => 'Northern Mariana Islands' ], 'iso3' => 'MNP', 'num' => '580' ],
		 'NO' => [ 'label' => [ 'en' => 'Norway' ], 'iso3' => 'NOR', 'num' => '578' ],
		 'OM' => [ 'label' => [ 'en' => 'Oman' ], 'iso3' => 'OMN', 'num' => '512' ],
		 'PK' => [ 'label' => [ 'en' => 'Pakistan' ], 'iso3' => 'PAK', 'num' => '586' ],
		 'PW' => [ 'label' => [ 'en' => 'Palau' ], 'iso3' => 'PLW', 'num' => '585' ],
		 'PS' => [ 'label' => [ 
		 		'en' => 'State of Palestine',
		 		'fr' => 'Palestine'], 'iso3' => 'PSE', 'num' => '275' ],
		 'PA' => [ 'label' => [ 'en' => 'Panama' ], 'iso3' => 'PAN', 'num' => '591' ],
		 'PG' => [ 'label' => [ 'en' => 'Papua New Guinea' ], 'iso3' => 'PNG', 'num' => '598' ],
		 'PY' => [ 'label' => [ 'en' => 'Paraguay' ], 'iso3' => 'PRY', 'num' => '600' ],
		 'PE' => [ 'label' => [ 
		          'en' => 'Peru',
		          'fr' => 'Pérou'], 'iso3' => 'PER', 'num' => '604' ],
		 'PH' => [ 'label' => [ 'en' => 'Philippines' ], 'iso3' => 'PHL', 'num' => '608' ],
		 'PN' => [ 'label' => [ 
		 		'en' => 'Pitcairn Islands',
		 		'fr' => 'Îles Pitcairn' ], 'iso3' => 'PCN', 'num' => '612' ],
		 'PL' => [ 'label' => [ 
		 		'en' => 'Poland',
		 		'fr' => 'Pologne' ], 'iso3' => 'POL', 'num' => '616' ],
		 'PT' => [ 'label' => [ 'en' => 'Portugal' ], 'iso3' => 'PRT', 'num' => '620' ],
		 'PR' => [ 'label' => [ 
		          'en' => 'Puerto Rico',
		          'fr' => 'Porto Rico'
		          ], 'iso3' => 'PRI', 'num' => '630' ],
		 'QA' => [ 'label' => [ 
		 		'en' => 'Qatar',
		 		'fr' => 'Qatar' // ou "Katar" mais tend à disparaître
		        ], 'iso3' => 'QAT', 'num' => '634' ],
		 'RE' => [ 'label' => [ 
		 		'en' => 'Réunion',
		 		'fr' => 'La Réunion' ], 'iso3' => 'REU', 'num' => '638' ],
		 'RO' => [ 'label' => [ 
		 		'en' => 'Romania',
		 		'fr' => "Roumanie" ], 'iso3' => 'ROU', 'num' => '642' ],
		 'RU' => [ 'label' => [ 
		 		'en' => 'Russia',
		 		'fr' => 'Russie'], 'iso3' => 'RUS', 'num' => '643' ],
		 'RW' => [ 'label' => [ 'en' => 'Rwanda' ], 'iso3' => 'RWA', 'num' => '646' ],
		 'BL' => [ 'label' => [ 'en' => 'Saint-Barthélemy' ], 'iso3' => 'BLM', 'num' => '652' ],
		 'SH' => [ 'label' => [ 
		 		'en' => 'Saint Helena',
		 		'fr' => 'Sainte-Hélène'], 'iso3' => 'SHN', 'num' => '654' ],
		 'KN' => [ 'label' => [ 'en' => 'Saint Kitts and Nevis' ], 'iso3' => 'KNA', 'num' => '659' ],
		 'LC' => [ 'label' => [ 
		 		'en' => 'Saint Lucia',
		 		'es' => 'Santa Lucía',
		 		'fr' => 'Sainte-Lucie'
		 		], 'iso3' => 'LCA', 'num' => '662' ],
		 'MF' => [ 'label' => [ 
		 		'en' => 'Saint-Martin -- french part' ], 'iso3' => 'MAF', 'num' => '663' ],
		 'PM' => [ 'label' => [ 
		 		'en' => 'Saint Pierre and Miquelon',
		 		'fr' => 'Saint Pierre et Miquelon' ], 'iso3' => 'SPM', 'num' => '666' ],
		 'VC' => [ 'label' => [ 
		 		'en' => 'Saint Vincent and the Grenadines',
				'es' => 'San Vicente y las Granadinas',
		 		'fr' => 'Saint-Vincent-et-les-Grenadines'
				 ], 'iso3' => 'VCT', 'num' => '670' ],
		 'WS' => [ 'label' => [ 'en' => 'Samoa' ], 'iso3' => 'WSM', 'num' => '882' ],
		 'SM' => [ 'label' => [ 'en' => 'San Marino' ], 'iso3' => 'SMR', 'num' => '674' ],
		 'ST' => [ 'label' => [ 'en' => 'Sao Tome and Principe' ], 'iso3' => 'STP', 'num' => '678' ],
		 'SA' => [ 'label' => [ 
		 		'en' => 'Saudi Arabia',
		 		'fr' => 'Arabie saoudite' ], 'iso3' => 'SAU', 'num' => '682' ],
		 'SN' => [ 'label' => [ 
		          'en' => 'Senegal',
		          'fr' => 'Sénégal' ], 'iso3' => 'SEN', 'num' => '686' ],
		 'RS' => [ 'label' => [ 
		 		'en' => 'Serbia',		 	
		 		'fr' => 'Serbie' ], 'iso3' => 'SRB', 'num' => '688' ],
		 'SC' => [ 'label' => [ 'en' => 'Seychelles' ], 'iso3' => 'SYC', 'num' => '690' ],
		 'SL' => [ 'label' => [ 'en' => 'Sierra Leone' ], 'iso3' => 'SLE', 'num' => '694' ],
		 'SG' => [ 'label' => [ 
		 		'en' => 'Singapore',
		 		'fr' => 'Singapour' ], 'iso3' => 'SGP', 'num' => '702' ],
		 'SK' => [ 'label' => [ 
		 		'en' => 'Slovakia',
		 		'fr' => 'Slovaquie' ], 'iso3' => 'SVK', 'num' => '703' ],
		 'SI' => [ 'label' => [ 'en' => 'Slovenia' ], 'iso3' => 'SVN', 'num' => '705' ],
		 'SB' => [ 'label' => [ 'en' => 'Solomon Islands' ], 'iso3' => 'SLB', 'num' => '090' ],
		 'SO' => [ 'label' => [ 
		          'en' => 'Somalia',
		          'fr' => 'Somalie' ], 'iso3' => 'SOM', 'num' => '706' ],
		 'ZA' => [ 'label' => [ 
		 		'en' => 'South Africa',
		 		'fr' => 'Afrique du Sud' ], 'iso3' => 'ZAF', 'num' => '710' ],
		 'GS' => [ 'label' => [ 
		 		'en' => 'South Georgia and the South Sandwich Islands',
		 		'fr' => 'Géorgie du Sud-et-les Îles Sandwich du Sud' ], 'iso3' => 'SGS', 'num' => '239' ],
		 'SS' => [ 'label' => [
		 		'en' => 'South Sudan',
		 		'fr' => 'Sud Soudan' ], 'iso3' => 'SSD', 'num' => '728' ],
		 'ES' => [ 'label' => [
		 		'de' => 'Spanien',
		 		'en' => 'Spain',
		 		'es' => 'España',
		 		'fr' => 'Espagne' ], 'iso3' => 'ESP', 'num' => '724' ],
		 'LK' => [ 'label' => [ 'en' => 'Sri Lanka' ], 'iso3' => 'LKA', 'num' => '144' ],
		 'SD' => [ 'label' => [ 'en' => 'Sudan' ], 'iso3' => 'SDN', 'num' => '736' ],
		 'SR' => [ 'label' => [ 'en' => 'Suriname' ], 'iso3' => 'SUR', 'num' => '740' ],
		 'SJ' => [ 'label' => [ 'en' => 'Svalbard and Jan Mayen Islands' ], 'iso3' => 'SJM', 'num' => '744' ],
		 'SZ' => [ 'label' => [ 'en' => 'Swaziland' ], 'iso3' => 'SWZ', 'num' => '748' ],
		 'SE' => [ 'label' => [ 
		 		'en' => 'Sweden',
		 		'fr' => 'Suède' ], 'iso3' => 'SWE', 'num' => '752' ],
		 'CH' => [ 'label' => [ 
		 		'en' => 'Switzerland', 
		 		'fr'=>'Suisse' ], 'iso3' => 'CHE', 'num' => '756' ],
		 'SY' => [ 'label' => [ 
		 		'en' => 'Syria',
		 		'fr' => 'Syrie' ], 'iso3' => 'SYR', 'num' => '760' ],
		 'TW' => [ 'label' => [
		 		'en' => 'Taiwan',
		 		'fr' => 'Taïwan'], 'iso3' => 'TWN', 'num' => '158' ],
		 'TJ' => [ 'label' => [ 
		 		'en' => 'Tajikistan' ], 'iso3' => 'TJK', 'num' => '762' ],
		 'TZ' => [ 'label' => [ 
		 		'en' => 'Tanzania',
		 		'fr' => 'Tanzanie' ], 'iso3' => 'TZA', 'num' => '834' ],
		 'TH' => [ 'label' => [ 
		 		'en' => 'Thailand',
		 		'fr' => 'Thailande' ], 'iso3' => 'THA', 'num' => '764' ],
		 'TL' => [ 'label' => [ 'en' => 'Timor-Leste' ], 'iso3' => 'TLS', 'num' => '626' ],
		 'TG' => [ 'label' => [ 'en' => 'Togo' ], 'iso3' => 'TGO', 'num' => '768' ],
		 'TK' => [ 'label' => [ 'en' => 'Tokelau' ], 'iso3' => 'TKL', 'num' => '772' ],
		 'TO' => [ 'label' => [ 'en' => 'Tonga' ], 'iso3' => 'TON', 'num' => '776' ],
		 'TT' => [ 'label' => [ 'en' => 'Trinidad and Tobago' ], 'iso3' => 'TTO', 'num' => '780' ],
		 'TN' => [ 'label' => [ 
		          'en' => 'Tunisia',
		          'fr' => 'Tunisie'
		          ], 'iso3' => 'TUN', 'num' => '788' ],
		 'TR' => [ 'label' => [ 
		 		'en' => 'Turkey',
		 		'fr' => 'Turquie' ], 'iso3' => 'TUR', 'num' => '792' ],
		 'TM' => [ 'label' => [ 'en' => 'Turkmenistan' ], 'iso3' => 'TKM', 'num' => '795' ],
		 'TC' => [ 'label' => [ 'en' => 'Turks and Caicos Islands' ], 'iso3' => 'TCA', 'num' => '796' ],
		 'TV' => [ 'label' => [ 'en' => 'Tuvalu' ], 'iso3' => 'TUV', 'num' => '798' ],
		 'UG' => [ 'label' => [ 
		 		'en' => 'Uganda',
		 		'fr' => 'Ouganda' ], 'iso3' => 'UGA', 'num' => '800' ],
		 'UA' => [ 'label' => [ 'en' => 'Ukraine' ], 'iso3' => 'UKR', 'num' => '804' ],
		 'AE' => [ 'label' => [
		 		'en' => 'United Arab Emirates',
		 		'fr' => 'Émirats arabes unis' ], 'iso3' => 'ARE', 'num' => '784' ],
		 'GB' => [ 'label' => [
		 		'de' => 'Vereinigtes Königreich',
		 		'en' => 'United Kingdom',
		 		'fr' => 'Royaume-Uni',
		 		'ja' => 'イギリス' ], 'iso3' => 'GBR', 'num' => '826' ],
		 'US' => [ 'label' => [ 
		 		'en' => 'United States',
		 		'fr' => 'États-Unis'], 'iso3' => 'USA', 'num' => '840' ],
		 'UM' => [ 'label' => [ 'en' => 'United States Minor Outlying Islands' ], 'iso3' => 'UMI', 'num' => '581' ],
		 'UY' => [ 'label' => [ 'en' => 'Uruguay' ], 'iso3' => 'URY', 'num' => '858' ],
		 'UZ' => [ 'label' => [ 'en' => 'Uzbekistan' ], 'iso3' => 'UZB', 'num' => '860' ],
		 'VU' => [ 'label' => [ 'en' => 'Vanuatu' ], 'iso3' => 'VUT', 'num' => '548' ],
		 'VE' => [ 'label' => [ 
		 		'en' => 'Venezuela',
		 		'fr' => 'Vénézuéla' ], 'iso3' => 'VEN', 'num' => '862' ],
		 'VN' => [ 'label' => [ 
		 		'en' => 'Vietnam',
		 		'fr' => 'Viêt Nam',
		 		'ja' => 'ベトナム' ], 'iso3' => 'VNM', 'num' => '704' ],
		 'VI' => [ 'label' => [ 
		 		'en' => 'US Virgin Islands' ], 'iso3' => 'VIR', 'num' => '850' ],
		 'WF' => [ 'label' => [ 'en' => 'Wallis and Futuna Islands' ], 'iso3' => 'WLF', 'num' => '876' ],
		 'XK' => [ 'label' => [ 
		 		'en' => 'Kosovo',
		 		'ja' => 'コソボ' ], 'iso3' => '', 'num' => '' ],
		 'EH' => [ 'label' => [ 'en' => 'Western Sahara' ], 'iso3' => 'ESH', 'num' => '732' ],
		 'YE' => [ 'label' => [ 'en' => 'Yemen' ], 'iso3' => 'YEM', 'num' => '887' ],
		 'ZM' => [ 'label' => [ 
		 		'en' => 'Zambia',
		 		'fr' => 'Zambie' ], 'iso3' => 'ZMB', 'num' => '894' ],
		 'ZW' => [ 'label' => [ 
		 		'en' => 'Zimbabwe',
		 		'fr' => 'Zimbabwe' ], 'iso3' => 'ZWE', 'num' => '716' ],
	];
	
	protected $data;
	protected $code;
	
	/**
	 * @deprecated use the static getCountryFromCode() instead.
	 * @param unknown $code
	 */
	public function __construct( $code ){
		$this->code = strtoupper($code);
		if( !isset( self::$COUNTRIES[ $this->code ] ) ){
			trigger_error("Code '$code' unknown for Country.");
			return;
		}
		$this->data = self::$COUNTRIES[ $this->code ];
	}
	
	/**
	 * Set the country using its code. Use
	 * 
	 * @param string $code the 2-letter code.
	 * @return \Concerto\Iso\Country|NULL
	 * @deprecated use getCountryFromLabel() instead.
	 */
	public static function getCountryFromCode($code){
		if( isset( self::$COUNTRIES[ $code ] ) ){
			return new Country($code);
		}
		return NULL;
	}
	
	/**
	 * @deprecated use getLabel instead.
	 * 
	 */
	public function label( $lang = 'en' ){
		$lang = substr( $lang, 0, 2 ); // We don't manage specificities (yet)
		$label = @$this->data['label'][$lang];
		if( !$label ){
			$label = @$this->data['label']['en'];
		}
		return $label;
	}
	
	/**
	 * Returns the label for this country in the
	 * requested language when available or english
	 * if no other language is found..
	 *
	 * @param string $lang the language to use. If not
	 * given, use the LANG environment variable
	 */
	public function getLabel( $lang = NULL ){
		if( !$lang ) $lang = getenv("LANG");
		$lang = substr( $lang, 0, 2 ); // We don't manage specificities (yet)
		$label = @$this->data['label'][$lang];
		if( !$label ){
			$label = @$this->data['label']['en'];
		}
		return $label;
	}
	
	/**
	 * Get all the known labels for this country.
	 * 
	 * @return array an associative array with the language 
	 *  as the key and the label as value.
	 * @since v0.57
	 */
	public function getLabels(){
		return @$this->data['label'];
	}
	
	/**
	 * Try to convert to a Country.
	 * 
	 * @param mixed $input the label which can be a code or a full label.
	 * @return NULL|\Concerto\Iso\Country
	 */
	static public function getCountryFromlabel($input){
	    if( !$input ){
	        return null;
	    }
	    
	    // Very convenient
	    if( $input instanceof Country){
	        return $input;
	    }
	    if(strlen($input) == 2 && self::$COUNTRIES[strtoupper($input)] ){
	        return new Country($input);
	    }
	    foreach(self::$COUNTRIES as $k => $country){
	        $input = std::lower($input);
	        foreach($country['label'] as $label){
	            if( std::lower($label) == $input ){
	                return new Country($k);
	            }
	        }
	    }
	    return null;
	}
	
	/**
	 * Returns the country code.
	 * 
	 */
	public function getCode(){
		return $this->code;
	}

	/**
	 * @deprecated use getCode() instead.
	 */
	public function code(){
		return $this->code;
	}
}
