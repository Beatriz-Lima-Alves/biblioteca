<?php
/**
 * Created by PhpStorm.
 * User: Raquel
 * Date: 28/01/2019
 * Time: 23:42
 */

namespace Useful\Controller;

use \GeoIp2\Database\Reader;

/**
 * Class GeoIPController
 * @package Useful\Controller
 */
class GeoIPController
{
    const MMDB_CITY = './data/GeoIP/GeoLite2-City.mmdb';
    const DAT_CITY = './data/GeoIP/GeoIP.dat';
    /**
     * Buscando GEO da cidade pelo id
     * @param $ip
     * @return bool|\GeoIp2\Model\City
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function city($ip) {
        // This creates the Reader object, which should be reused across lookups.
        $reader = new Reader ( self::MMDB_CITY );
        try {
            $record = $reader->city ( $ip );
            return $record;
        } catch ( \Exception $e ) {
        }
        return false;
    }
    /**
     * Coordenadas pelo dat
     * @param $ip
     * @return array
     */
    public function geoipByData($ip) {
        $gi = geoip_open ( self::DAT_CITY, GEOIP_STANDARD );
        $record = array (
            'country_code' => geoip_country_code_by_addr ( $gi, $ip ),
            'country_name' => geoip_country_name_by_addr ( $gi, $ip )
        );
        geoip_close ( $gi );
        return $record;
    }
}