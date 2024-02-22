<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CountriesControllerTest extends TestCase
{
    const STRAPI_CMS_API_URL = 'STRAPI_CMS_API_URL';
    
    public function test_get_countries(): void
    {
        $strapiUrl = \Illuminate\Support\Facades\Config::get(
            'strapi.strapi_cms_api_url'
        );
        
        Http::preventStrayRequests();
        
        Http::fake([
            $strapiUrl . '/countries' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'attributes' => [
                            'name' => 'Gambia',
                            'createdAt' => '2024-02-21T10:11:33.528Z',
                            'updatedAt' => '2024-02-21T10:13:31.587Z',
                            'publishedAt' => '2024-02-21T10:13:31.585Z',
                            'iso_code' => 'GM',
                        ],
                    ],
                    [
                        'id' => 2,
                        'attributes' => [
                            'name' => 'Ghana',
                            'createdAt' => '2024-02-21T10:14:04.823Z',
                            'updatedAt' => '2024-02-21T10:28:28.407Z',
                            'publishedAt' => '2024-02-21T10:28:28.406Z',
                            'iso_code' => 'GH',
                        ],
                    ]
                ]
            ], 200)
        ]);
        
        Http::fake([
            $strapiUrl . '/airlines' => Http::response([
                'data' => [
                    [
                        'id' => 1,
                        'attributes' => [
                            'name' => '1Time Airline',
                            'createdAt' => '2024-02-21T18:05:36.883Z',
                            'updatedAt' => '2024-02-21T18:05:39.264Z',
                            'publishedAt' => '2024-02-21T18:05:39.262Z',
                            'iata_code' => '1T',
                            'icao_code' => 'RNX',
                            'alias' => null,
                        ],
                    ],
                    [
                        'id' => 2,
                        'attributes' => [
                            'name' => 'All Nippon Airways',
                            'createdAt' => '2024-02-21T18:06:39.177Z',
                            'updatedAt' => '2024-02-21T18:06:51.696Z',
                            'publishedAt' => '2024-02-21T18:06:51.693Z',
                            'iata_code' => 'NH',
                            'icao_code' => 'ANA',
                            'alias' => 'ANA All Nippon Airways',
                        ],
                    ],
                ]
            ], 200)
        ]);
        

        
        $response = $this->get('/api/getCountries');
        
        $numberOfCountries = 2;
        $numberOfAirlines = 2;
              
        $response
            ->assertJsonCount($numberOfCountries, 'countries')
            ->assertJsonCount($numberOfAirlines, 'airlines')
            ->assertJsonPath('countries.0.attributes.name', 'Gambia')
            ->assertJsonFragment([
                'id' => 1,
                'attributes' => [
                    'name' => 'Gambia',
                    'createdAt' => '2024-02-21T10:11:33.528Z',
                    'updatedAt' => '2024-02-21T10:13:31.587Z',
                    'publishedAt' => '2024-02-21T10:13:31.585Z',
                    'iso_code' => 'GM'
                ]
            ])
            ->assertJsonPath('airlines.0.attributes.name', '1Time Airline')
            ->assertJsonFragment([
                'id' => 1,
                'attributes' => [
                    'name' => '1Time Airline',
                    'createdAt' => '2024-02-21T18:05:36.883Z',
                    'updatedAt' => '2024-02-21T18:05:39.264Z',
                    'publishedAt' => '2024-02-21T18:05:39.262Z',
                    'iata_code' => '1T',
                    'icao_code' => 'RNX',
                    'alias' => null
                ]
            ]);

        $response->assertOk();
    }
}
