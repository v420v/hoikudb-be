<?php

return [
    'api_key' => env('GOOGLE_GEOCODER_API_KEY'),
    'api_url' => env('GOOGLE_GEOCODER_API_URL', 'https://maps.googleapis.com/maps/api/geocode/json'),
];
