<?php

namespace Useful\Controller;

use Geocoder\Model\AddressCollection;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\ReverseQuery;

/**
 * Class GeocoderController
 * @package Useful\Controller
 */
class GeocoderController
{

    protected $httpClient;

    protected $provider;

    protected $geocoder;

    /**
     * GeocoderController constructor.
     * @param $apiKey
     * @param string $locale
     * @param null $region
     */
    public function __construct($apiKey, $locale = 'pt', $region = null)
    {
        $this->httpClient = new \Http\Adapter\Guzzle6\Client();
        $this->provider = new \Geocoder\Provider\GoogleMaps\GoogleMaps($this->httpClient, $region, $apiKey);
        $this->geocoder = new \Geocoder\StatefulGeocoder($this->provider, $locale);
    }

    /**
     * Busca de coordenadas pelo endereco
     * @param $search
     * @return object
     * @throws \Geocoder\Exception\Exception
     */
    public function geocode($search)
    {
        $data = (object)[
            'lat' => null,
            'lng' => null,
            'check_geocoding' => false
        ];
        // Busca
        try {
            if (strlen($search) > 4) {
                $result = $this->geocoder->geocodeQuery(GeocodeQuery::create($search));
                if ($result->count() > 0) {
                    $Address = $result->first();
                    $data->lat = $Address->getLatitude();
                    $data->lng = $Address->getLongitude();
                    $data->check_geocoding = true;
                }
            }
        } catch (\Exception $e) {
            echo " \n --------------------------- \n\n\n";
            echo "Error: " . $e->getMessage() . "\n\n";
            echo " \n --------------------------- \n\n\n";
        }
        // Resultado
        return $data;
    }

    /**
     * Busca pelas coordenadas
     * @param $latitude
     * @param $longitude
     * @return bool
     * @throws \Geocoder\Exception\Exception
     */
    public function reverse($latitude, $longitude)
    {
        try {
            $result = $this->geocoder->reverseQuery(ReverseQuery::fromCoordinates($latitude, $longitude));
            if ($result->count() > 0) {
                return $result->first();
            }
        } catch (\Exception $e) {
            echo " \n --------------------------- \n\n\n";
            echo "Error: " . $e->getMessage() . "\n\n";
            echo " \n --------------------------- \n\n\n";
        }

        // Resultado
        return false;
    }

    public function query(string $address, string $state, $country = 'Brasil')
    {
        // Busca
        try {
            if (strlen($address) >= 10) {
                $query = GeocodeQuery::create("$address, ACRE, $country")->withLimit(1);
                $result = $this->geocoder->geocodeQuery($query);
                if ($result->count() > 0) {
                    return $result;
                }
            }
        } catch (\Exception $e) {
//            echo " \n --------------------------- \n\n\n";
//            echo "Error: " . $e->getMessage() . "\n\n";
//            echo " \n --------------------------- \n\n\n";
        }
        return new AddressCollection();
    }
}