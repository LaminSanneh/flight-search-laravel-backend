<?php

namespace App\Services\StrapiFlight\Requests;

class SearchFlightRequest {

    public string $fromAirportLocation;
    public string $toAirportLocation;
    public string $fromDate;
    public ?string $toDate = null;

    public function __construct(
        string $fromAirportLocation,
        string $toAirportLocation,
        string $fromDate,
        string $toDate = null
    ) {
        
        $this->fromAirportLocation = $fromAirportLocation;
        $this->toAirportLocation = $toAirportLocation;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }
    
    public static function create(
            string $fromAirportLocation,
            string $toAirportLocation,
            string $fromDate,
            string $toDate
    ) {
        return new self(
            $fromAirportLocation,
            $toAirportLocation,
            $fromDate,
            $toDate
        );
    }
}
