<?php

namespace AmazonScraper;

use GuzzleHttp\Client as GuzzleClient;

class Client {
    private $guzzleClient;
    public function __construct(array $config = [])
    {
        if (!isset($config['rapidapi_key'])) {
            throw new \Exception('rapidapi_key must be set');
        }
        
        $this->guzzleClient = new GuzzleClient([
            // Base URI is used with relative requests
            'base_uri' => 'https://amazon23.p.rapidapi.com',
            // You can set any number of default request options.
            'timeout'  => 30,
            'headers' => [
                'x-rapidapi-host' => 'amazon23.p.rapidapi.com',
                'x-rapidapi-key' => $config['rapidapi_key'] // get your key on https://rapidapi.com/restyler/api/amazon23
            ]
        ]);

    }

    public function getProductDetails(array $params = []) {
        if (!isset($params['asin'])) {
            throw new \Exception('params[asin] is required!');
        }
        
        $response = $this->guzzleClient->get('product-details', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }

    public function getProductReviews(array $params = []) {
        if (!isset($params['asin'])) {
            throw new \Exception('params[asin] is required!');
        }

        if (!isset($params['sort_by'])) {
            throw new \Exception('params[sort_by] is required (recent/helpful)');
        }

        $response = $this->guzzleClient->get('reviews', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }

    public function searchProducts($params) {
        if (!isset($params['query'])) {
            throw new \Exception('params[query] is required!');
        }

        $response = $this->guzzleClient->get('product-search', ['query' => $params]);
    
        return json_decode($response->getBody(), true);

    }


}