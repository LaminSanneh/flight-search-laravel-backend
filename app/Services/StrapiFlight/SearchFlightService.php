<?php

namespace App\Services\StrapiFlight;

use \Illuminate\Http\Client\Pool;

class SearchFlightService {

    private string $strapiUrl;
    private string $strapiApiKey;

    public function __construct() {
        $this->strapiUrl = \Illuminate\Support\Facades\Config::get(
            'strapi.strapi_cms_api_url'
        );
        
        $this->strapiApiKey = \Illuminate\Support\Facades\Config::get(
            'strapi.strapi_cms_api_token'
        );
    }

    public function getFlightResults(Requests\SearchFlightRequest $request) {
        
        $responses = \Illuminate\Support\Facades\Http::pool(
            function (
                Pool $pool
            ) use ($request) {
            
            $outboundQueryParams = [
                'filters' => [
                    'origin_airport_code' => [
                        '$eq' => $request->fromAirportLocation
                    ],
                    'destination_airport_code' => [
                        '$eq' => $request->toAirportLocation
                    ],
                    'departure_time' => [
                        '$gte' => $request->fromDate
                    ]
                ]
            ];

            $pools = [
                $pool
                    ->as('outbound_flights')
                    ->withToken($this->strapiApiKey)
                    ->withQueryParameters($outboundQueryParams)
                    ->get($this->strapiUrl . '/flights')
            ];

            if ($request->toDate !== null) {
                
                $inboundQueryParams = [
                    'filters' => [
                        'origin_airport_code' => [
                            '$eq' => $request->toAirportLocation
                        ],
                        'destination_airport_code' => [
                            '$eq' => $request->fromAirportLocation
                        ],
                        'departure_time' => [
                            '$gte' => $request->toDate
                        ]
                    ]
                ];
                
                $pools[] = $pool
                    ->as('return_flights')
                    ->withToken($this->strapiApiKey)
                    ->withQueryParameters($inboundQueryParams)
                    ->get($this->strapiUrl . '/flights');
            }
            
            return $pools;
        });

        return $this->formatMultipleStrapiResults($responses);
    }
    
    private function formatMultipleStrapiResults(array $responses)
    {
        $formatted = [];

        foreach ($responses as $key => $val) {
            $formatted[$key] = $val['data'];
        }

        return $formatted;
    }
}
