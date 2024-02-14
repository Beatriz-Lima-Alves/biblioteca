<?php
/**
 * Created by PhpStorm.
 * User: Raquel
 * Date: 28/01/2019
 * Time: 19:52
 */

namespace Useful\Controller;

use Carbon\Carbon;

/**
 * Class TimezoneController
 * @package Useful\Controller
 */
class TimezoneController
{
    /**
     * Get the timezone list
     * @return array
     */
    public static function getTimezoneList(): array
    {
        return [
            'Africa/Abidjan (UTC/GMT +00:00)' => 'Africa/Abidjan',
            'Africa/Accra (UTC/GMT +00:00)' => 'Africa/Accra',
            'Africa/Addis_Ababa (UTC/GMT +03:00)' => 'Africa/Addis_Ababa',
            'Africa/Algiers (UTC/GMT +01:00)' => 'Africa/Algiers',
            'Africa/Asmara (UTC/GMT +03:00)' => 'Africa/Asmara',
            'Africa/Bamako (UTC/GMT +00:00)' => 'Africa/Bamako',
            'Africa/Bangui (UTC/GMT +01:00)' => 'Africa/Bangui',
            'Africa/Banjul (UTC/GMT +00:00)' => 'Africa/Banjul',
            'Africa/Bissau (UTC/GMT +00:00)' => 'Africa/Bissau',
            'Africa/Blantyre (UTC/GMT +02:00)' => 'Africa/Blantyre',
            'Africa/Brazzaville (UTC/GMT +01:00)' => 'Africa/Brazzaville',
            'Africa/Bujumbura (UTC/GMT +02:00)' => 'Africa/Bujumbura',
            'Africa/Cairo (UTC/GMT +02:00)' => 'Africa/Cairo',
            'Africa/Casablanca (UTC/GMT +00:00)' => 'Africa/Casablanca',
            'Africa/Ceuta (UTC/GMT +01:00)' => 'Africa/Ceuta',
            'Africa/Conakry (UTC/GMT +00:00)' => 'Africa/Conakry',
            'Africa/Dakar (UTC/GMT +00:00)' => 'Africa/Dakar',
            'Africa/Dar_es_Salaam (UTC/GMT +03:00)' => 'Africa/Dar_es_Salaam',
            'Africa/Djibouti (UTC/GMT +03:00)' => 'Africa/Djibouti',
            'Africa/Douala (UTC/GMT +01:00)' => 'Africa/Douala',
            'Africa/El_Aaiun (UTC/GMT +00:00)' => 'Africa/El_Aaiun',
            'Africa/Freetown (UTC/GMT +00:00)' => 'Africa/Freetown',
            'Africa/Gaborone (UTC/GMT +02:00)' => 'Africa/Gaborone',
            'Africa/Harare (UTC/GMT +02:00)' => 'Africa/Harare',
            'Africa/Johannesburg (UTC/GMT +02:00)' => 'Africa/Johannesburg',
            'Africa/Juba (UTC/GMT +03:00)' => 'Africa/Juba',
            'Africa/Kampala (UTC/GMT +03:00)' => 'Africa/Kampala',
            'Africa/Khartoum (UTC/GMT +03:00)' => 'Africa/Khartoum',
            'Africa/Kigali (UTC/GMT +02:00)' => 'Africa/Kigali',
            'Africa/Kinshasa (UTC/GMT +01:00)' => 'Africa/Kinshasa',
            'Africa/Lagos (UTC/GMT +01:00)' => 'Africa/Lagos',
            'Africa/Libreville (UTC/GMT +01:00)' => 'Africa/Libreville',
            'Africa/Lome (UTC/GMT +00:00)' => 'Africa/Lome',
            'Africa/Luanda (UTC/GMT +01:00)' => 'Africa/Luanda',
            'Africa/Lubumbashi (UTC/GMT +02:00)' => 'Africa/Lubumbashi',
            'Africa/Lusaka (UTC/GMT +02:00)' => 'Africa/Lusaka',
            'Africa/Malabo (UTC/GMT +01:00)' => 'Africa/Malabo',
            'Africa/Maputo (UTC/GMT +02:00)' => 'Africa/Maputo',
            'Africa/Maseru (UTC/GMT +02:00)' => 'Africa/Maseru',
            'Africa/Mbabane (UTC/GMT +02:00)' => 'Africa/Mbabane',
            'Africa/Mogadishu (UTC/GMT +03:00)' => 'Africa/Mogadishu',
            'Africa/Monrovia (UTC/GMT +00:00)' => 'Africa/Monrovia',
            'Africa/Nairobi (UTC/GMT +03:00)' => 'Africa/Nairobi',
            'Africa/Ndjamena (UTC/GMT +01:00)' => 'Africa/Ndjamena',
            'Africa/Niamey (UTC/GMT +01:00)' => 'Africa/Niamey',
            'Africa/Nouakchott (UTC/GMT +00:00)' => 'Africa/Nouakchott',
            'Africa/Ouagadougou (UTC/GMT +00:00)' => 'Africa/Ouagadougou',
            'Africa/Porto-Novo (UTC/GMT +01:00)' => 'Africa/Porto-Novo',
            'Africa/Sao_Tome (UTC/GMT +00:00)' => 'Africa/Sao_Tome',
            'Africa/Tripoli (UTC/GMT +02:00)' => 'Africa/Tripoli',
            'Africa/Tunis (UTC/GMT +01:00)' => 'Africa/Tunis',
            'Africa/Windhoek (UTC/GMT +02:00)' => 'Africa/Windhoek',
            'America/Adak (UTC/GMT -10:00)' => 'America/Adak',
            'America/Anchorage (UTC/GMT -09:00)' => 'America/Anchorage',
            'America/Anguilla (UTC/GMT -04:00)' => 'America/Anguilla',
            'America/Antigua (UTC/GMT -04:00)' => 'America/Antigua',
            'America/Araguaina (UTC/GMT -03:00)' => 'America/Araguaina',
            'America/Argentina/Buenos_Aires (UTC/GMT -03:00)' => 'America/Argentina/Buenos_Aires',
            'America/Argentina/Catamarca (UTC/GMT -03:00)' => 'America/Argentina/Catamarca',
            'America/Argentina/Cordoba (UTC/GMT -03:00)' => 'America/Argentina/Cordoba',
            'America/Argentina/Jujuy (UTC/GMT -03:00)' => 'America/Argentina/Jujuy',
            'America/Argentina/La_Rioja (UTC/GMT -03:00)' => 'America/Argentina/La_Rioja',
            'America/Argentina/Mendoza (UTC/GMT -03:00)' => 'America/Argentina/Mendoza',
            'America/Argentina/Rio_Gallegos (UTC/GMT -03:00)' => 'America/Argentina/Rio_Gallegos',
            'America/Argentina/Salta (UTC/GMT -03:00)' => 'America/Argentina/Salta',
            'America/Argentina/San_Juan (UTC/GMT -03:00)' => 'America/Argentina/San_Juan',
            'America/Argentina/San_Luis (UTC/GMT -03:00)' => 'America/Argentina/San_Luis',
            'America/Argentina/Tucuman (UTC/GMT -03:00)' => 'America/Argentina/Tucuman',
            'America/Argentina/Ushuaia (UTC/GMT -03:00)' => 'America/Argentina/Ushuaia',
            'America/Aruba (UTC/GMT -04:00)' => 'America/Aruba',
            'America/Asuncion (UTC/GMT -03:00)' => 'America/Asuncion',
            'America/Atikokan (UTC/GMT -05:00)' => 'America/Atikokan',
            'America/Bahia (UTC/GMT -03:00)' => 'America/Bahia',
            'America/Bahia_Banderas (UTC/GMT -06:00)' => 'America/Bahia_Banderas',
            'America/Barbados (UTC/GMT -04:00)' => 'America/Barbados',
            'America/Belem (UTC/GMT -03:00)' => 'America/Belem',
            'America/Belize (UTC/GMT -06:00)' => 'America/Belize',
            'America/Blanc-Sablon (UTC/GMT -04:00)' => 'America/Blanc-Sablon',
            'America/Boa_Vista (UTC/GMT -04:00)' => 'America/Boa_Vista',
            'America/Bogota (UTC/GMT -05:00)' => 'America/Bogota',
            'America/Boise (UTC/GMT -07:00)' => 'America/Boise',
            'America/Cambridge_Bay (UTC/GMT -07:00)' => 'America/Cambridge_Bay',
            'America/Campo_Grande (UTC/GMT -03:00)' => 'America/Campo_Grande',
            'America/Cancun (UTC/GMT -05:00)' => 'America/Cancun',
            'America/Caracas (UTC/GMT -04:00)' => 'America/Caracas',
            'America/Cayenne (UTC/GMT -03:00)' => 'America/Cayenne',
            'America/Cayman (UTC/GMT -05:00)' => 'America/Cayman',
            'America/Chicago (UTC/GMT -06:00)' => 'America/Chicago',
            'America/Chihuahua (UTC/GMT -07:00)' => 'America/Chihuahua',
            'America/Costa_Rica (UTC/GMT -06:00)' => 'America/Costa_Rica',
            'America/Creston (UTC/GMT -07:00)' => 'America/Creston',
            'America/Cuiaba (UTC/GMT -03:00)' => 'America/Cuiaba',
            'America/Curacao (UTC/GMT -04:00)' => 'America/Curacao',
            'America/Danmarkshavn (UTC/GMT +00:00)' => 'America/Danmarkshavn',
            'America/Dawson (UTC/GMT -08:00)' => 'America/Dawson',
            'America/Dawson_Creek (UTC/GMT -07:00)' => 'America/Dawson_Creek',
            'America/Denver (UTC/GMT -07:00)' => 'America/Denver',
            'America/Detroit (UTC/GMT -05:00)' => 'America/Detroit',
            'America/Dominica (UTC/GMT -04:00)' => 'America/Dominica',
            'America/Edmonton (UTC/GMT -07:00)' => 'America/Edmonton',
            'America/Eirunepe (UTC/GMT -05:00)' => 'America/Eirunepe',
            'America/El_Salvador (UTC/GMT -06:00)' => 'America/El_Salvador',
            'America/Fort_Nelson (UTC/GMT -07:00)' => 'America/Fort_Nelson',
            'America/Fortaleza (UTC/GMT -03:00)' => 'America/Fortaleza',
            'America/Glace_Bay (UTC/GMT -04:00)' => 'America/Glace_Bay',
            'America/Godthab (UTC/GMT -03:00)' => 'America/Godthab',
            'America/Goose_Bay (UTC/GMT -04:00)' => 'America/Goose_Bay',
            'America/Grand_Turk (UTC/GMT -04:00)' => 'America/Grand_Turk',
            'America/Grenada (UTC/GMT -04:00)' => 'America/Grenada',
            'America/Guadeloupe (UTC/GMT -04:00)' => 'America/Guadeloupe',
            'America/Guatemala (UTC/GMT -06:00)' => 'America/Guatemala',
            'America/Guayaquil (UTC/GMT -05:00)' => 'America/Guayaquil',
            'America/Guyana (UTC/GMT -04:00)' => 'America/Guyana',
            'America/Halifax (UTC/GMT -04:00)' => 'America/Halifax',
            'America/Havana (UTC/GMT -05:00)' => 'America/Havana',
            'America/Hermosillo (UTC/GMT -07:00)' => 'America/Hermosillo',
            'America/Indiana/Indianapolis (UTC/GMT -05:00)' => 'America/Indiana/Indianapolis',
            'America/Indiana/Knox (UTC/GMT -06:00)' => 'America/Indiana/Knox',
            'America/Indiana/Marengo (UTC/GMT -05:00)' => 'America/Indiana/Marengo',
            'America/Indiana/Petersburg (UTC/GMT -05:00)' => 'America/Indiana/Petersburg',
            'America/Indiana/Tell_City (UTC/GMT -06:00)' => 'America/Indiana/Tell_City',
            'America/Indiana/Vevay (UTC/GMT -05:00)' => 'America/Indiana/Vevay',
            'America/Indiana/Vincennes (UTC/GMT -05:00)' => 'America/Indiana/Vincennes',
            'America/Indiana/Winamac (UTC/GMT -05:00)' => 'America/Indiana/Winamac',
            'America/Inuvik (UTC/GMT -07:00)' => 'America/Inuvik',
            'America/Iqaluit (UTC/GMT -05:00)' => 'America/Iqaluit',
            'America/Jamaica (UTC/GMT -05:00)' => 'America/Jamaica',
            'America/Juneau (UTC/GMT -09:00)' => 'America/Juneau',
            'America/Kentucky/Louisville (UTC/GMT -05:00)' => 'America/Kentucky/Louisville',
            'America/Kentucky/Monticello (UTC/GMT -05:00)' => 'America/Kentucky/Monticello',
            'America/Kralendijk (UTC/GMT -04:00)' => 'America/Kralendijk',
            'America/La_Paz (UTC/GMT -04:00)' => 'America/La_Paz',
            'America/Lima (UTC/GMT -05:00)' => 'America/Lima',
            'America/Los_Angeles (UTC/GMT -08:00)' => 'America/Los_Angeles',
            'America/Lower_Princes (UTC/GMT -04:00)' => 'America/Lower_Princes',
            'America/Maceio (UTC/GMT -03:00)' => 'America/Maceio',
            'America/Managua (UTC/GMT -06:00)' => 'America/Managua',
            'America/Manaus (UTC/GMT -04:00)' => 'America/Manaus',
            'America/Marigot (UTC/GMT -04:00)' => 'America/Marigot',
            'America/Martinique (UTC/GMT -04:00)' => 'America/Martinique',
            'America/Matamoros (UTC/GMT -06:00)' => 'America/Matamoros',
            'America/Mazatlan (UTC/GMT -07:00)' => 'America/Mazatlan',
            'America/Menominee (UTC/GMT -06:00)' => 'America/Menominee',
            'America/Merida (UTC/GMT -06:00)' => 'America/Merida',
            'America/Metlakatla (UTC/GMT -09:00)' => 'America/Metlakatla',
            'America/Mexico_City (UTC/GMT -06:00)' => 'America/Mexico_City',
            'America/Miquelon (UTC/GMT -03:00)' => 'America/Miquelon',
            'America/Moncton (UTC/GMT -04:00)' => 'America/Moncton',
            'America/Monterrey (UTC/GMT -06:00)' => 'America/Monterrey',
            'America/Montevideo (UTC/GMT -03:00)' => 'America/Montevideo',
            'America/Montserrat (UTC/GMT -04:00)' => 'America/Montserrat',
            'America/Nassau (UTC/GMT -05:00)' => 'America/Nassau',
            'America/New_York (UTC/GMT -05:00)' => 'America/New_York',
            'America/Nipigon (UTC/GMT -05:00)' => 'America/Nipigon',
            'America/Nome (UTC/GMT -09:00)' => 'America/Nome',
            'America/Noronha (UTC/GMT -02:00)' => 'America/Noronha',
            'America/North_Dakota/Beulah (UTC/GMT -06:00)' => 'America/North_Dakota/Beulah',
            'America/North_Dakota/Center (UTC/GMT -06:00)' => 'America/North_Dakota/Center',
            'America/North_Dakota/New_Salem (UTC/GMT -06:00)' => 'America/North_Dakota/New_Salem',
            'America/Ojinaga (UTC/GMT -07:00)' => 'America/Ojinaga',
            'America/Panama (UTC/GMT -05:00)' => 'America/Panama',
            'America/Pangnirtung (UTC/GMT -05:00)' => 'America/Pangnirtung',
            'America/Paramaribo (UTC/GMT -03:00)' => 'America/Paramaribo',
            'America/Phoenix (UTC/GMT -07:00)' => 'America/Phoenix',
            'America/Port-au-Prince (UTC/GMT -05:00)' => 'America/Port-au-Prince',
            'America/Port_of_Spain (UTC/GMT -04:00)' => 'America/Port_of_Spain',
            'America/Porto_Velho (UTC/GMT -04:00)' => 'America/Porto_Velho',
            'America/Puerto_Rico (UTC/GMT -04:00)' => 'America/Puerto_Rico',
            'America/Rainy_River (UTC/GMT -06:00)' => 'America/Rainy_River',
            'America/Rankin_Inlet (UTC/GMT -06:00)' => 'America/Rankin_Inlet',
            'America/Recife (UTC/GMT -03:00)' => 'America/Recife',
            'America/Regina (UTC/GMT -06:00)' => 'America/Regina',
            'America/Resolute (UTC/GMT -06:00)' => 'America/Resolute',
            'America/Rio_Branco (UTC/GMT -05:00)' => 'America/Rio_Branco',
            'America/Santarem (UTC/GMT -03:00)' => 'America/Santarem',
            'America/Santiago (UTC/GMT -03:00)' => 'America/Santiago',
            'America/Santo_Domingo (UTC/GMT -04:00)' => 'America/Santo_Domingo',
            'America/Sao_Paulo (UTC/GMT -02:00)' => 'America/Sao_Paulo',
            'America/Scoresbysund (UTC/GMT -01:00)' => 'America/Scoresbysund',
            'America/Sitka (UTC/GMT -09:00)' => 'America/Sitka',
            'America/St_Barthelemy (UTC/GMT -04:00)' => 'America/St_Barthelemy',
            'America/St_Johns (UTC/GMT -03:30)' => 'America/St_Johns',
            'America/St_Kitts (UTC/GMT -04:00)' => 'America/St_Kitts',
            'America/St_Lucia (UTC/GMT -04:00)' => 'America/St_Lucia',
            'America/St_Thomas (UTC/GMT -04:00)' => 'America/St_Thomas',
            'America/St_Vincent (UTC/GMT -04:00)' => 'America/St_Vincent',
            'America/Swift_Current (UTC/GMT -06:00)' => 'America/Swift_Current',
            'America/Tegucigalpa (UTC/GMT -06:00)' => 'America/Tegucigalpa',
            'America/Thule (UTC/GMT -04:00)' => 'America/Thule',
            'America/Thunder_Bay (UTC/GMT -05:00)' => 'America/Thunder_Bay',
            'America/Tijuana (UTC/GMT -08:00)' => 'America/Tijuana',
            'America/Toronto (UTC/GMT -05:00)' => 'America/Toronto',
            'America/Tortola (UTC/GMT -04:00)' => 'America/Tortola',
            'America/Vancouver (UTC/GMT -08:00)' => 'America/Vancouver',
            'America/Whitehorse (UTC/GMT -08:00)' => 'America/Whitehorse',
            'America/Winnipeg (UTC/GMT -06:00)' => 'America/Winnipeg',
            'America/Yakutat (UTC/GMT -09:00)' => 'America/Yakutat',
            'America/Yellowknife (UTC/GMT -07:00)' => 'America/Yellowknife',
            'Antarctica/Casey (UTC/GMT +11:00)' => 'Antarctica/Casey',
            'Antarctica/Davis (UTC/GMT +07:00)' => 'Antarctica/Davis',
            'Antarctica/DumontDUrville (UTC/GMT +10:00)' => 'Antarctica/DumontDUrville',
            'Antarctica/Macquarie (UTC/GMT +11:00)' => 'Antarctica/Macquarie',
            'Antarctica/Mawson (UTC/GMT +05:00)' => 'Antarctica/Mawson',
            'Antarctica/McMurdo (UTC/GMT +13:00)' => 'Antarctica/McMurdo',
            'Antarctica/Palmer (UTC/GMT -03:00)' => 'Antarctica/Palmer',
            'Antarctica/Rothera (UTC/GMT -03:00)' => 'Antarctica/Rothera',
            'Antarctica/Syowa (UTC/GMT +03:00)' => 'Antarctica/Syowa',
            'Antarctica/Troll (UTC/GMT +00:00)' => 'Antarctica/Troll',
            'Antarctica/Vostok (UTC/GMT +06:00)' => 'Antarctica/Vostok',
            'Arctic/Longyearbyen (UTC/GMT +01:00)' => 'Arctic/Longyearbyen',
            'Asia/Aden (UTC/GMT +03:00)' => 'Asia/Aden',
            'Asia/Almaty (UTC/GMT +06:00)' => 'Asia/Almaty',
            'Asia/Amman (UTC/GMT +02:00)' => 'Asia/Amman',
            'Asia/Anadyr (UTC/GMT +12:00)' => 'Asia/Anadyr',
            'Asia/Aqtau (UTC/GMT +05:00)' => 'Asia/Aqtau',
            'Asia/Aqtobe (UTC/GMT +05:00)' => 'Asia/Aqtobe',
            'Asia/Ashgabat (UTC/GMT +05:00)' => 'Asia/Ashgabat',
            'Asia/Atyrau (UTC/GMT +05:00)' => 'Asia/Atyrau',
            'Asia/Baghdad (UTC/GMT +03:00)' => 'Asia/Baghdad',
            'Asia/Bahrain (UTC/GMT +03:00)' => 'Asia/Bahrain',
            'Asia/Baku (UTC/GMT +04:00)' => 'Asia/Baku',
            'Asia/Bangkok (UTC/GMT +07:00)' => 'Asia/Bangkok',
            'Asia/Barnaul (UTC/GMT +07:00)' => 'Asia/Barnaul',
            'Asia/Beirut (UTC/GMT +02:00)' => 'Asia/Beirut',
            'Asia/Bishkek (UTC/GMT +06:00)' => 'Asia/Bishkek',
            'Asia/Brunei (UTC/GMT +08:00)' => 'Asia/Brunei',
            'Asia/Chita (UTC/GMT +09:00)' => 'Asia/Chita',
            'Asia/Choibalsan (UTC/GMT +08:00)' => 'Asia/Choibalsan',
            'Asia/Colombo (UTC/GMT +05:30)' => 'Asia/Colombo',
            'Asia/Damascus (UTC/GMT +02:00)' => 'Asia/Damascus',
            'Asia/Dhaka (UTC/GMT +06:00)' => 'Asia/Dhaka',
            'Asia/Dili (UTC/GMT +09:00)' => 'Asia/Dili',
            'Asia/Dubai (UTC/GMT +04:00)' => 'Asia/Dubai',
            'Asia/Dushanbe (UTC/GMT +05:00)' => 'Asia/Dushanbe',
            'Asia/Famagusta (UTC/GMT +03:00)' => 'Asia/Famagusta',
            'Asia/Gaza (UTC/GMT +02:00)' => 'Asia/Gaza',
            'Asia/Hebron (UTC/GMT +02:00)' => 'Asia/Hebron',
            'Asia/Ho_Chi_Minh (UTC/GMT +07:00)' => 'Asia/Ho_Chi_Minh',
            'Asia/Hong_Kong (UTC/GMT +08:00)' => 'Asia/Hong_Kong',
            'Asia/Hovd (UTC/GMT +07:00)' => 'Asia/Hovd',
            'Asia/Irkutsk (UTC/GMT +08:00)' => 'Asia/Irkutsk',
            'Asia/Jakarta (UTC/GMT +07:00)' => 'Asia/Jakarta',
            'Asia/Jayapura (UTC/GMT +09:00)' => 'Asia/Jayapura',
            'Asia/Jerusalem (UTC/GMT +02:00)' => 'Asia/Jerusalem',
            'Asia/Kabul (UTC/GMT +04:30)' => 'Asia/Kabul',
            'Asia/Kamchatka (UTC/GMT +12:00)' => 'Asia/Kamchatka',
            'Asia/Karachi (UTC/GMT +05:00)' => 'Asia/Karachi',
            'Asia/Kathmandu (UTC/GMT +05:45)' => 'Asia/Kathmandu',
            'Asia/Khandyga (UTC/GMT +09:00)' => 'Asia/Khandyga',
            'Asia/Kolkata (UTC/GMT +05:30)' => 'Asia/Kolkata',
            'Asia/Krasnoyarsk (UTC/GMT +07:00)' => 'Asia/Krasnoyarsk',
            'Asia/Kuala_Lumpur (UTC/GMT +08:00)' => 'Asia/Kuala_Lumpur',
            'Asia/Kuching (UTC/GMT +08:00)' => 'Asia/Kuching',
            'Asia/Kuwait (UTC/GMT +03:00)' => 'Asia/Kuwait',
            'Asia/Macau (UTC/GMT +08:00)' => 'Asia/Macau',
            'Asia/Magadan (UTC/GMT +11:00)' => 'Asia/Magadan',
            'Asia/Makassar (UTC/GMT +08:00)' => 'Asia/Makassar',
            'Asia/Manila (UTC/GMT +08:00)' => 'Asia/Manila',
            'Asia/Muscat (UTC/GMT +04:00)' => 'Asia/Muscat',
            'Asia/Nicosia (UTC/GMT +02:00)' => 'Asia/Nicosia',
            'Asia/Novokuznetsk (UTC/GMT +07:00)' => 'Asia/Novokuznetsk',
            'Asia/Novosibirsk (UTC/GMT +07:00)' => 'Asia/Novosibirsk',
            'Asia/Omsk (UTC/GMT +06:00)' => 'Asia/Omsk',
            'Asia/Oral (UTC/GMT +05:00)' => 'Asia/Oral',
            'Asia/Phnom_Penh (UTC/GMT +07:00)' => 'Asia/Phnom_Penh',
            'Asia/Pontianak (UTC/GMT +07:00)' => 'Asia/Pontianak',
            'Asia/Pyongyang (UTC/GMT +08:30)' => 'Asia/Pyongyang',
            'Asia/Qatar (UTC/GMT +03:00)' => 'Asia/Qatar',
            'Asia/Qyzylorda (UTC/GMT +06:00)' => 'Asia/Qyzylorda',
            'Asia/Riyadh (UTC/GMT +03:00)' => 'Asia/Riyadh',
            'Asia/Sakhalin (UTC/GMT +11:00)' => 'Asia/Sakhalin',
            'Asia/Samarkand (UTC/GMT +05:00)' => 'Asia/Samarkand',
            'Asia/Seoul (UTC/GMT +09:00)' => 'Asia/Seoul',
            'Asia/Shanghai (UTC/GMT +08:00)' => 'Asia/Shanghai',
            'Asia/Singapore (UTC/GMT +08:00)' => 'Asia/Singapore',
            'Asia/Srednekolymsk (UTC/GMT +11:00)' => 'Asia/Srednekolymsk',
            'Asia/Taipei (UTC/GMT +08:00)' => 'Asia/Taipei',
            'Asia/Tashkent (UTC/GMT +05:00)' => 'Asia/Tashkent',
            'Asia/Tbilisi (UTC/GMT +04:00)' => 'Asia/Tbilisi',
            'Asia/Tehran (UTC/GMT +03:30)' => 'Asia/Tehran',
            'Asia/Thimphu (UTC/GMT +06:00)' => 'Asia/Thimphu',
            'Asia/Tokyo (UTC/GMT +09:00)' => 'Asia/Tokyo',
            'Asia/Tomsk (UTC/GMT +07:00)' => 'Asia/Tomsk',
            'Asia/Ulaanbaatar (UTC/GMT +08:00)' => 'Asia/Ulaanbaatar',
            'Asia/Urumqi (UTC/GMT +06:00)' => 'Asia/Urumqi',
            'Asia/Ust-Nera (UTC/GMT +10:00)' => 'Asia/Ust-Nera',
            'Asia/Vientiane (UTC/GMT +07:00)' => 'Asia/Vientiane',
            'Asia/Vladivostok (UTC/GMT +10:00)' => 'Asia/Vladivostok',
            'Asia/Yakutsk (UTC/GMT +09:00)' => 'Asia/Yakutsk',
            'Asia/Yangon (UTC/GMT +06:30)' => 'Asia/Yangon',
            'Asia/Yekaterinburg (UTC/GMT +05:00)' => 'Asia/Yekaterinburg',
            'Asia/Yerevan (UTC/GMT +04:00)' => 'Asia/Yerevan',
            'Atlantic/Azores (UTC/GMT -01:00)' => 'Atlantic/Azores',
            'Atlantic/Bermuda (UTC/GMT -04:00)' => 'Atlantic/Bermuda',
            'Atlantic/Canary (UTC/GMT +00:00)' => 'Atlantic/Canary',
            'Atlantic/Cape_Verde (UTC/GMT -01:00)' => 'Atlantic/Cape_Verde',
            'Atlantic/Faroe (UTC/GMT +00:00)' => 'Atlantic/Faroe',
            'Atlantic/Madeira (UTC/GMT +00:00)' => 'Atlantic/Madeira',
            'Atlantic/Reykjavik (UTC/GMT +00:00)' => 'Atlantic/Reykjavik',
            'Atlantic/South_Georgia (UTC/GMT -02:00)' => 'Atlantic/South_Georgia',
            'Atlantic/St_Helena (UTC/GMT +00:00)' => 'Atlantic/St_Helena',
            'Atlantic/Stanley (UTC/GMT -03:00)' => 'Atlantic/Stanley',
            'Australia/Adelaide (UTC/GMT +10:30)' => 'Australia/Adelaide',
            'Australia/Brisbane (UTC/GMT +10:00)' => 'Australia/Brisbane',
            'Australia/Broken_Hill (UTC/GMT +10:30)' => 'Australia/Broken_Hill',
            'Australia/Currie (UTC/GMT +11:00)' => 'Australia/Currie',
            'Australia/Darwin (UTC/GMT +09:30)' => 'Australia/Darwin',
            'Australia/Eucla (UTC/GMT +08:45)' => 'Australia/Eucla',
            'Australia/Hobart (UTC/GMT +11:00)' => 'Australia/Hobart',
            'Australia/Lindeman (UTC/GMT +10:00)' => 'Australia/Lindeman',
            'Australia/Lord_Howe (UTC/GMT +11:00)' => 'Australia/Lord_Howe',
            'Australia/Melbourne (UTC/GMT +11:00)' => 'Australia/Melbourne',
            'Australia/Perth (UTC/GMT +08:00)' => 'Australia/Perth',
            'Australia/Sydney (UTC/GMT +11:00)' => 'Australia/Sydney',
            'Europe/Amsterdam (UTC/GMT +01:00)' => 'Europe/Amsterdam',
            'Europe/Andorra (UTC/GMT +01:00)' => 'Europe/Andorra',
            'Europe/Astrakhan (UTC/GMT +04:00)' => 'Europe/Astrakhan',
            'Europe/Athens (UTC/GMT +02:00)' => 'Europe/Athens',
            'Europe/Belgrade (UTC/GMT +01:00)' => 'Europe/Belgrade',
            'Europe/Berlin (UTC/GMT +01:00)' => 'Europe/Berlin',
            'Europe/Bratislava (UTC/GMT +01:00)' => 'Europe/Bratislava',
            'Europe/Brussels (UTC/GMT +01:00)' => 'Europe/Brussels',
            'Europe/Bucharest (UTC/GMT +02:00)' => 'Europe/Bucharest',
            'Europe/Budapest (UTC/GMT +01:00)' => 'Europe/Budapest',
            'Europe/Busingen (UTC/GMT +01:00)' => 'Europe/Busingen',
            'Europe/Chisinau (UTC/GMT +02:00)' => 'Europe/Chisinau',
            'Europe/Copenhagen (UTC/GMT +01:00)' => 'Europe/Copenhagen',
            'Europe/Dublin (UTC/GMT +00:00)' => 'Europe/Dublin',
            'Europe/Gibraltar (UTC/GMT +01:00)' => 'Europe/Gibraltar',
            'Europe/Guernsey (UTC/GMT +00:00)' => 'Europe/Guernsey',
            'Europe/Helsinki (UTC/GMT +02:00)' => 'Europe/Helsinki',
            'Europe/Isle_of_Man (UTC/GMT +00:00)' => 'Europe/Isle_of_Man',
            'Europe/Istanbul (UTC/GMT +03:00)' => 'Europe/Istanbul',
            'Europe/Jersey (UTC/GMT +00:00)' => 'Europe/Jersey',
            'Europe/Kaliningrad (UTC/GMT +02:00)' => 'Europe/Kaliningrad',
            'Europe/Kiev (UTC/GMT +02:00)' => 'Europe/Kiev',
            'Europe/Kirov (UTC/GMT +03:00)' => 'Europe/Kirov',
            'Europe/Lisbon (UTC/GMT +00:00)' => 'Europe/Lisbon',
            'Europe/Ljubljana (UTC/GMT +01:00)' => 'Europe/Ljubljana',
            'Europe/London (UTC/GMT +00:00)' => 'Europe/London',
            'Europe/Luxembourg (UTC/GMT +01:00)' => 'Europe/Luxembourg',
            'Europe/Madrid (UTC/GMT +01:00)' => 'Europe/Madrid',
            'Europe/Malta (UTC/GMT +01:00)' => 'Europe/Malta',
            'Europe/Mariehamn (UTC/GMT +02:00)' => 'Europe/Mariehamn',
            'Europe/Minsk (UTC/GMT +03:00)' => 'Europe/Minsk',
            'Europe/Monaco (UTC/GMT +01:00)' => 'Europe/Monaco',
            'Europe/Moscow (UTC/GMT +03:00)' => 'Europe/Moscow',
            'Europe/Oslo (UTC/GMT +01:00)' => 'Europe/Oslo',
            'Europe/Paris (UTC/GMT +01:00)' => 'Europe/Paris',
            'Europe/Podgorica (UTC/GMT +01:00)' => 'Europe/Podgorica',
            'Europe/Prague (UTC/GMT +01:00)' => 'Europe/Prague',
            'Europe/Riga (UTC/GMT +02:00)' => 'Europe/Riga',
            'Europe/Rome (UTC/GMT +01:00)' => 'Europe/Rome',
            'Europe/Samara (UTC/GMT +04:00)' => 'Europe/Samara',
            'Europe/San_Marino (UTC/GMT +01:00)' => 'Europe/San_Marino',
            'Europe/Sarajevo (UTC/GMT +01:00)' => 'Europe/Sarajevo',
            'Europe/Saratov (UTC/GMT +04:00)' => 'Europe/Saratov',
            'Europe/Simferopol (UTC/GMT +03:00)' => 'Europe/Simferopol',
            'Europe/Skopje (UTC/GMT +01:00)' => 'Europe/Skopje',
            'Europe/Sofia (UTC/GMT +02:00)' => 'Europe/Sofia',
            'Europe/Stockholm (UTC/GMT +01:00)' => 'Europe/Stockholm',
            'Europe/Tallinn (UTC/GMT +02:00)' => 'Europe/Tallinn',
            'Europe/Tirane (UTC/GMT +01:00)' => 'Europe/Tirane',
            'Europe/Ulyanovsk (UTC/GMT +04:00)' => 'Europe/Ulyanovsk',
            'Europe/Uzhgorod (UTC/GMT +02:00)' => 'Europe/Uzhgorod',
            'Europe/Vaduz (UTC/GMT +01:00)' => 'Europe/Vaduz',
            'Europe/Vatican (UTC/GMT +01:00)' => 'Europe/Vatican',
            'Europe/Vienna (UTC/GMT +01:00)' => 'Europe/Vienna',
            'Europe/Vilnius (UTC/GMT +02:00)' => 'Europe/Vilnius',
            'Europe/Volgograd (UTC/GMT +03:00)' => 'Europe/Volgograd',
            'Europe/Warsaw (UTC/GMT +01:00)' => 'Europe/Warsaw',
            'Europe/Zagreb (UTC/GMT +01:00)' => 'Europe/Zagreb',
            'Europe/Zaporozhye (UTC/GMT +02:00)' => 'Europe/Zaporozhye',
            'Europe/Zurich (UTC/GMT +01:00)' => 'Europe/Zurich',
            'Indian/Antananarivo (UTC/GMT +03:00)' => 'Indian/Antananarivo',
            'Indian/Chagos (UTC/GMT +06:00)' => 'Indian/Chagos',
            'Indian/Christmas (UTC/GMT +07:00)' => 'Indian/Christmas',
            'Indian/Cocos (UTC/GMT +06:30)' => 'Indian/Cocos',
            'Indian/Comoro (UTC/GMT +03:00)' => 'Indian/Comoro',
            'Indian/Kerguelen (UTC/GMT +05:00)' => 'Indian/Kerguelen',
            'Indian/Mahe (UTC/GMT +04:00)' => 'Indian/Mahe',
            'Indian/Maldives (UTC/GMT +05:00)' => 'Indian/Maldives',
            'Indian/Mauritius (UTC/GMT +04:00)' => 'Indian/Mauritius',
            'Indian/Mayotte (UTC/GMT +03:00)' => 'Indian/Mayotte',
            'Indian/Reunion (UTC/GMT +04:00)' => 'Indian/Reunion',
            'Pacific/Apia (UTC/GMT +14:00)' => 'Pacific/Apia',
            'Pacific/Auckland (UTC/GMT +13:00)' => 'Pacific/Auckland',
            'Pacific/Bougainville (UTC/GMT +11:00)' => 'Pacific/Bougainville',
            'Pacific/Chatham (UTC/GMT +13:45)' => 'Pacific/Chatham',
            'Pacific/Chuuk (UTC/GMT +10:00)' => 'Pacific/Chuuk',
            'Pacific/Easter (UTC/GMT -05:00)' => 'Pacific/Easter',
            'Pacific/Efate (UTC/GMT +11:00)' => 'Pacific/Efate',
            'Pacific/Enderbury (UTC/GMT +13:00)' => 'Pacific/Enderbury',
            'Pacific/Fakaofo (UTC/GMT +13:00)' => 'Pacific/Fakaofo',
            'Pacific/Fiji (UTC/GMT +12:00)' => 'Pacific/Fiji',
            'Pacific/Funafuti (UTC/GMT +12:00)' => 'Pacific/Funafuti',
            'Pacific/Galapagos (UTC/GMT -06:00)' => 'Pacific/Galapagos',
            'Pacific/Gambier (UTC/GMT -09:00)' => 'Pacific/Gambier',
            'Pacific/Guadalcanal (UTC/GMT +11:00)' => 'Pacific/Guadalcanal',
            'Pacific/Guam (UTC/GMT +10:00)' => 'Pacific/Guam',
            'Pacific/Honolulu (UTC/GMT -10:00)' => 'Pacific/Honolulu',
            'Pacific/Johnston (UTC/GMT -10:00)' => 'Pacific/Johnston',
            'Pacific/Kiritimati (UTC/GMT +14:00)' => 'Pacific/Kiritimati',
            'Pacific/Kosrae (UTC/GMT +11:00)' => 'Pacific/Kosrae',
            'Pacific/Kwajalein (UTC/GMT +12:00)' => 'Pacific/Kwajalein',
            'Pacific/Majuro (UTC/GMT +12:00)' => 'Pacific/Majuro',
            'Pacific/Marquesas (UTC/GMT -09:30)' => 'Pacific/Marquesas',
            'Pacific/Midway (UTC/GMT -11:00)' => 'Pacific/Midway',
            'Pacific/Nauru (UTC/GMT +12:00)' => 'Pacific/Nauru',
            'Pacific/Niue (UTC/GMT -11:00)' => 'Pacific/Niue',
            'Pacific/Norfolk (UTC/GMT +11:00)' => 'Pacific/Norfolk',
            'Pacific/Noumea (UTC/GMT +11:00)' => 'Pacific/Noumea',
            'Pacific/Pago_Pago (UTC/GMT -11:00)' => 'Pacific/Pago_Pago',
            'Pacific/Palau (UTC/GMT +09:00)' => 'Pacific/Palau',
            'Pacific/Pitcairn (UTC/GMT -08:00)' => 'Pacific/Pitcairn',
            'Pacific/Pohnpei (UTC/GMT +11:00)' => 'Pacific/Pohnpei',
            'Pacific/Port_Moresby (UTC/GMT +10:00)' => 'Pacific/Port_Moresby',
            'Pacific/Rarotonga (UTC/GMT -10:00)' => 'Pacific/Rarotonga',
            'Pacific/Saipan (UTC/GMT +10:00)' => 'Pacific/Saipan',
            'Pacific/Tahiti (UTC/GMT -10:00)' => 'Pacific/Tahiti',
            'Pacific/Tarawa (UTC/GMT +12:00)' => 'Pacific/Tarawa',
            'Pacific/Tongatapu (UTC/GMT +13:00)' => 'Pacific/Tongatapu',
            'Pacific/Wake (UTC/GMT +12:00)' => 'Pacific/Wake',
            'Pacific/Wallis (UTC/GMT +12:00)' => 'Pacific/Wallis',
            'UTC (UTC/GMT -02:00)' => 'UTC',
        ];
    }

    /**
     * Timezones list with GMT offset
     *
     * @return array
     * @link http://stackoverflow.com/a/9328760
     */
    public static function tzList()
    {
        $zones_array = array();
        $timestamp = time();
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }
        return $zones_array;
    }

    /**
     * Imprime timezonelist
     */
    public static function tzToString()
    {

        $print = "";
        foreach (self::tzList() as $t) {
            $a = $t['zone'];
            $b = $t['diff_from_GMT'];
            //$print .= "<option value='{$a}'>$a ({$b})</option> \n";
            $print .= "'$a ({$b})'=>'{$a}',\n";
        }

        echo $print;
    }

    /**
     * Detect the timezone id(s) from an offset and dst
     *
     * @param $offset
     * @param $dst
     * @param bool $multiple
     * @param string $default
     * @return array|mixed
     */
    public static function detectTimezoneId($offset, $dst, $multiple = FALSE, $default = 'UTC')
    {
        $detected_timezone_ids = array();

        // Get the timezone list
        $timezones = self::getTimezoneList();

        // Try to find a timezone for which both the offset and dst match
        foreach ($timezones as $timezone_id) {
            $timezone_data = self::getTimezoneData($timezone_id);
            if ($timezone_data['offset'] == $offset && $dst == $timezone_data['dst']) {
                array_push($detected_timezone_ids, $timezone_id);
                if (!$multiple)
                    break;
            }
        }

        if (empty($detected_timezone_ids)) {
            $detected_timezone_ids = array($default);
        }

        return $multiple ? $detected_timezone_ids : $detected_timezone_ids[0];
    }

    /**
     * Get the current offset and dst for the given timezone id
     * @param $timezone_id
     * @return array
     */
    public static function getTimezoneData($timezone_id)
    {
        $date = new DateTime("now");
        $date->setTimezone(timezone_open($timezone_id));

        return array(
            'offset' => $date->getOffset() / 3600,
            'dst' => intval(date_format($date, "I"))
        );
    }

    /**
     * Timezone default
     * @return string
     */
    public static function getDefaultTimezone(): string
    {
        return 'UTC';
        //return self::getCurrentTimezone();
    }

    /**
     * Timezone default
     * @return string
     */
    public static function getCurrentTimezone(): string
    {
        if (date_default_timezone_get()) {
            return date_default_timezone_get();
        }

        if (ini_get('date.timezone')) {
            return ini_get('date.timezone');
        }
        return self::getDefaultTimezone();
    }

    /**
     * Compara a data com a timezone e converte para o horario de acordo com a timezone
     * @param string $created_at
     * @param string $timezone
     * @param $gmtTimezone
     * @param string $gmtFormat
     * @param string $locale
     * @return string
     */
    public static function getDateTimeWithTimeZone(string $created_at, string $timezone, $gmtTimezone, $gmtFormat = 'Y-m-d H:i:s', $locale = 'pt_BR'): string
    {
        //var_dump($created_at, $timezone, $gmtFormat, $gmtFormat, $locale); exit;
        if (ValidadorController::isValidTimezone($timezone) && ValidadorController::isValidTimezone($gmtTimezone)) {
            $mutable = Carbon::now()->locale($locale);
            $current = Carbon::createFromFormat('Y-m-d H:i:s', $created_at, $timezone);
            $current->setTimezone($gmtTimezone);
            $result = $current->format($gmtFormat);
            //Fix issue translate
            if ($gmtFormat == 'F, d, Y H:i:s' && strpos(strtolower($mutable->monthName), strtolower($result)) === false) {
                $result = str_replace($current->monthName, ucfirst($mutable->monthName), $result);
            }
            return $result;
        }
        return $created_at;
    }

    /**
     * Converte o formato sem levar em consideracao a timezone
     * @param string $date
     * @param string $format
     * @param string $locale
     * @return string
     */
    public static function getDateTimeWithoutTimeZone(string $date, $format = 'Y-m-d H:i:s', $locale = 'pt_BR'): string
    {
        $mutable = Carbon::now()->locale($locale);
        $current = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $result = $current->format($format);
        //Fix issue translate
        if ($format == 'F, d, Y H:i:s' && strpos(strtolower($mutable->monthName), strtolower($result)) === false) {
            $result = str_replace($current->monthName, ucfirst($mutable->monthName), $result);
        }
        return $result;
    }
}