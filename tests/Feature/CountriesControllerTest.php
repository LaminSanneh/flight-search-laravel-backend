<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CountriesControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_countries(): void
    {
        $response = $this->get('/api/getCountries');
        
        $numberOfCountries = 3;
        $numberOfAirlines = 2;
        
//        $response->dd();
              
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
                        'iso_code' => 'GM',
                    ]
                ]);

        $response->assertOk();
    }
}
