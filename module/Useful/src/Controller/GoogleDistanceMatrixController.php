<?php


namespace Useful\Controller;

/**
 * Class GoogleDistanceMatrixController
 * @package Useful\Controller
 * Author Claudio
 * Date 15/02/2021
 * Time 14:36
 */
class GoogleDistanceMatrixController
{

    protected $gmaps;
    protected $key;

    /**
     * GoogleDistanceMatrixController constructor.
     */
    public function __construct($key)
    {
        $this->key = $key;
        $this->gmaps = new \yidas\googleMaps\Client(['key' => $key]);
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    public function getDistanceMatrix($start, $end)
    {
        // With units
        return $this->gmaps->distanceMatrix($end, $start, [
            'units' => 'metric',
            'mode' => 'driving',
            'sensor' => false
        ]);
    }

    /**
     * @param $start
     * @param $end
     * @return mixed
     * @throws \Exception
     */
    public function getDistanceMatrixContent($lat_start, $lng_start, $lat_end, $lng_end)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?mode=driving&origins={$lat_start},{$lng_start}&destinations={$lat_end},{$lng_end}&key={$this->key}";
        //request the directions
        $res = file_get_contents($url);
        $distance = json_decode($res);
        if ($distance->status == 'OK') {
            // Code to run after status OK
            $element = $distance->rows[0]->elements[0];
            return [
                'destination_addresses' => $distance->destination_addresses[0] ?? '',
                'origin_addresses' => $distance->origin_addresses[0] ?? '',
                'distance_text' => $element->distance->text,
                'distance_value' => $element->distance->value,
                'duration_text' => $element->duration->text,
                'duration_value' => $element->duration->value,
            ];
        } else {
            throw new \Exception('The request was Invalid');
        }
    }
}