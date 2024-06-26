<?php

namespace App\Http\Controllers;

class CountriesController extends Controller
{
    public function getCountries()
    {
        $strapiUrl = \Illuminate\Support\Facades\Config::get(
            'strapi.strapi_cms_api_url'
        );

        $strapiApiKey = \Illuminate\Support\Facades\Config::get(
            'strapi.strapi_cms_api_token'
        );

        $responses =
            \Illuminate\Support\Facades\Http::pool(
                fn (
                    \Illuminate\Http\Client\Pool $pool
                ) => [
                    $pool
                        ->as('countries')
                        ->withToken($strapiApiKey)
                        ->get($strapiUrl . '/countries'),
                    $pool
                        ->as('airlines')
                        ->withToken($strapiApiKey)
                        ->get($strapiUrl . '/airlines')
                ]
            );

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
