<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SearchFlightsControllerTest extends TestCase
{
    public function testSearchFlightsOutboundAndReturn(): void
    {
//        https://www.jetcost.co.uk/flights/search?trips%5B0%5D%5Bfrom_iata%5D=AGP&trips%5B0%5D%5Bto_iata%5D=POS&trips%5B0%5D%5Bdate%5D=2024-02-22&trips%5B1%5D%5Bfrom_iata%5D=POS&trips%5B1%5D%5Bto_iata%5D=AGP&trips%5B1%5D%5Bdate%5D=2024-03-08&adults=1&children=0&infants=0&cabin_class=0&source=dHJhdmVsLW1ldGF8&utm_campaign=UK-EN_mal_F_C_JC_checkboxes_desktop&utm_source=mal&utm_medium=cpc&utm_id=mal&utm_term=AGP-POS
        $params = $this->getRequestParams();
        
//        trips[0][from_iata]: AGP
//        trips[0][to_iata]: POS
//        trips[0][date]: 2024-02-22
//        trips[1][from_iata]: POS
//        trips[1][to_iata]: AGP
//        trips[1][date]: 2024-03-08
//        adults: 1
//        children: 0
//        infants: 0
//        cabin_class: 0
//        source: dHJhdmVsLW1ldGF8
//        utm_campaign: UK-EN_mal_F_C_JC_checkboxes_desktop
//        utm_source: mal
//        utm_medium: cpc
//        utm_id: mal
//        utm_term: AGP-POS
        
        
        
//        https://compare.mauction.app/a26s/v1/click/flight?from=AGP&to=POS&departureDate=02/25/2024&returnDate=02/26/2024&numTravelers=1&campaignID=24863&publisherID=3130&referralURL=wl_GB_4_3_1_3_1_1_1_0_0_0_20240222&position=2&displayType=1&searchKey=926004d0c742ac44794fb5b9ac604749&auctionType=200&ignoreChromeRedirect=true&isPopUnder=true&searchDisplayType=99&maxSearchesPerDay=86400&hardLimitSearchCap=9999&hardLimitSearchCapSeconds=1&siteId=hotelscan
        
//        from: AGP
//        to: POS
//        departureDate: 02/25/2024
//        returnDate: 02/26/2024
//        numTravelers: 1
//        campaignID: 24863
//        publisherID: 3130
//        referralURL: wl_GB_4_3_1_3_1_1_1_0_0_0_20240222
//        position: 2
//        displayType: 1
//        searchKey: 926004d0c742ac44794fb5b9ac604749
//        auctionType: 200
//        ignoreChromeRedirect: true
//        isPopUnder: true
//        searchDisplayType: 99
//        maxSearchesPerDay: 86400
//        hardLimitSearchCap: 9999
//        hardLimitSearchCapSeconds: 1
//        siteId: hotelscan
        
        Http::preventStrayRequests();
        
        $strapiUrl = Config::get(
            'strapi.strapi_cms_api_url'
        );
        
        $mockedFlightResultsData = $this->getMockedFlightResultData();
        
        Http::fake([
            $strapiUrl . '/flights*' => Http::sequence()
                ->push([
                    'data' => $mockedFlightResultsData['outbound']
                ])
                ->push([
                    'data' => $mockedFlightResultsData['return']
                ])
        ]);
        
        
        $numberOfFlightResults = 2;
        $response = $this->call('GET', '/api/searchFlights', $params);
                
        $response
            ->assertJsonCount($numberOfFlightResults, 'outbound_flights')
            ->assertJsonPath('outbound_flights.0.attributes.origin_airport_code', 'KZN')
            ->assertJsonPath('outbound_flights.0.attributes.destination_airport_code', 'AER')
            ->assertJsonPath('outbound_flights.1.attributes.origin_airport_code', 'KZN')
            ->assertJsonPath('outbound_flights.1.attributes.destination_airport_code', 'AER')
            ->assertJsonFragment([
                'outbound_flights' => $mockedFlightResultsData['outbound']
            ])
            ->assertJsonCount($numberOfFlightResults, 'return_flights')
            ->assertJsonPath('return_flights.0.attributes.origin_airport_code', 'AER')
            ->assertJsonPath('return_flights.0.attributes.destination_airport_code', 'KZN')
            ->assertJsonPath('return_flights.1.attributes.origin_airport_code', 'AER')
            ->assertJsonPath('return_flights.1.attributes.destination_airport_code', 'KZN')
            ->assertJsonFragment([
                'return_flights' => $mockedFlightResultsData['return']
            ]);

        $response->assertOk();
    }
    
    public function testSearchFlightsOneWay(): void
    {
//        https://www.jetcost.co.uk/flights/search?trips%5B0%5D%5Bfrom_iata%5D=AGP&trips%5B0%5D%5Bto_iata%5D=POS&trips%5B0%5D%5Bdate%5D=2024-02-22&trips%5B1%5D%5Bfrom_iata%5D=POS&trips%5B1%5D%5Bto_iata%5D=AGP&trips%5B1%5D%5Bdate%5D=2024-03-08&adults=1&children=0&infants=0&cabin_class=0&source=dHJhdmVsLW1ldGF8&utm_campaign=UK-EN_mal_F_C_JC_checkboxes_desktop&utm_source=mal&utm_medium=cpc&utm_id=mal&utm_term=AGP-POS
        $params = $this->getRequestParams();
        
        unset($params['to_date']);
        
//        trips[0][from_iata]: AGP
//        trips[0][to_iata]: POS
//        trips[0][date]: 2024-02-22
//        trips[1][from_iata]: POS
//        trips[1][to_iata]: AGP
//        trips[1][date]: 2024-03-08
//        adults: 1
//        children: 0
//        infants: 0
//        cabin_class: 0
//        source: dHJhdmVsLW1ldGF8
//        utm_campaign: UK-EN_mal_F_C_JC_checkboxes_desktop
//        utm_source: mal
//        utm_medium: cpc
//        utm_id: mal
//        utm_term: AGP-POS
        
        
        
//        https://compare.mauction.app/a26s/v1/click/flight?from=AGP&to=POS&departureDate=02/25/2024&returnDate=02/26/2024&numTravelers=1&campaignID=24863&publisherID=3130&referralURL=wl_GB_4_3_1_3_1_1_1_0_0_0_20240222&position=2&displayType=1&searchKey=926004d0c742ac44794fb5b9ac604749&auctionType=200&ignoreChromeRedirect=true&isPopUnder=true&searchDisplayType=99&maxSearchesPerDay=86400&hardLimitSearchCap=9999&hardLimitSearchCapSeconds=1&siteId=hotelscan
        
//        from: AGP
//        to: POS
//        departureDate: 02/25/2024
//        returnDate: 02/26/2024
//        numTravelers: 1
//        campaignID: 24863
//        publisherID: 3130
//        referralURL: wl_GB_4_3_1_3_1_1_1_0_0_0_20240222
//        position: 2
//        displayType: 1
//        searchKey: 926004d0c742ac44794fb5b9ac604749
//        auctionType: 200
//        ignoreChromeRedirect: true
//        isPopUnder: true
//        searchDisplayType: 99
//        maxSearchesPerDay: 86400
//        hardLimitSearchCap: 9999
//        hardLimitSearchCapSeconds: 1
//        siteId: hotelscan
        
        Http::preventStrayRequests();
        
        $strapiUrl = Config::get(
            'strapi.strapi_cms_api_url'
        );
        
        $mockedFlightResultsData = $this->getMockedFlightResultData();
        
        Http::fake([
            $strapiUrl . '/flights*' => Http::sequence()
                ->push([
                    'data' => $mockedFlightResultsData['outbound']
                ])
        ]);
        
        
        $numberOfFlightResults = 2;
        $response = $this->call('GET', '/api/searchFlights', $params);
        
        $response
            ->assertJsonCount($numberOfFlightResults, 'outbound_flights')
            ->assertJsonPath('outbound_flights.0.attributes.origin_airport_code', 'KZN')
            ->assertJsonPath('outbound_flights.0.attributes.destination_airport_code', 'AER')
            ->assertJsonPath('outbound_flights.1.attributes.origin_airport_code', 'KZN')
            ->assertJsonPath('outbound_flights.1.attributes.destination_airport_code', 'AER')
            ->assertJsonFragment([
                'outbound_flights' => $mockedFlightResultsData['outbound']
            ])
            ->assertJsonMissingPath('return_flights');

        $response->assertOk();
    }
    
    private function getRequestParams(array $overRidingParams = []) {
        return array_merge(
            [
                'from_date' => '2024-03-15',
                'to_date' => '2024-04-22',
                'from_location' => 'KZN',
                'to_location' => 'AER',
                'adults' => 1,
                'children' => 0,
                'infants' => 0
            ],
            $overRidingParams
        );
    }

    private function getMockedFlightResultData() {
        $flightResult1Outbound = [
            'id' => 1,
            'attributes' => [
                'origin_airport_code' => 'KZN',
                'destination_airport_code' => 'AER',
                'airline_iata_code' => '2B',
                'airline_icao_code' => 'ARD',
                'departure_time' => '2024-07-01 13:00',
                'arrival_time' => '2024-07-02 15:00',
            ]
        ];
        
        $flightResult2Outbound = [
            'id' => 2,
            'attributes' => [
                'origin_airport_code' => 'KZN',
                'destination_airport_code' => 'AER',
                'airline_iata_code' => '',
                'airline_icao_code' => 'AQN',
                'departure_time' => '2024-07-01 17:00',
                'arrival_time' => '2024-07-02 19:00',
            ]
        ];
        
        $flightResult1Return = [
            'id' => 1,
            'attributes' => [
                'origin_airport_code' => 'AER',
                'destination_airport_code' => 'KZN',
                'airline_iata_code' => '2B',
                'airline_icao_code' => 'ARD',
                'departure_time' => '2024-07-01 13:00',
                'arrival_time' => '2024-07-02 15:00',
            ]
        ];
        
        $flightResult2Return = [
            'id' => 2,
            'attributes' => [
                'origin_airport_code' => 'AER',
                'destination_airport_code' => 'KZN',
                'airline_iata_code' => '',
                'airline_icao_code' => 'AQN',
                'departure_time' => '2024-07-01 17:00',
                'arrival_time' => '2024-07-02 19:00',
            ]
        ];
        
        return [
            'outbound' => [
                $flightResult1Outbound,
                $flightResult2Outbound
            ],
            'return' => [
                $flightResult1Return,
                $flightResult2Return
            ]
        ];
    }
}
