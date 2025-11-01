<?php

namespace App\Service;

use GuzzleHttp\Client;
use Exception;

class GoogleGeocodingService
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    const STATUS_OK = 200;

    public function getCoordinates(string $address): array
    {
        try {
            $response = $this->client->get(config('google-geocoder.api_url'), [
                'query' => [
                    'address' => $address,
                    'key' => config('google-geocoder.api_key'),
                    'language' => 'ja',
                    'region' => 'jp'
                ],
                'timeout' => 30,
            ]);

            if ($response->getStatusCode() !== self::STATUS_OK) {
                throw new Exception('HTTP Error ' . $response->getStatusCode() . ' for ' . $address);
            }

            $responseData = json_decode($response->getBody(), true);

            return $responseData;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
