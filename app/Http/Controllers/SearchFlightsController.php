<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;

class SearchFlightsController extends Controller
{

    private \App\Services\StrapiFlight\SearchFlightService $searchFlightService;

    public function __construct(\App\Services\StrapiFlight\SearchFlightService $searchFlightService) {
        
        $this->searchFlightService = $searchFlightService;
    }
    
    public function searchFlights(Request $request) {
        $searchFlightRequest = new \App\Services\StrapiFlight\Requests\SearchFlightRequest(
            $request->get('from_location'),
            $request->get('to_location'),
            $request->get('from_date'),
            $request->get('to_date')
        );
            
        return $this->searchFlightService->getFlightResults($searchFlightRequest);
    }
}
