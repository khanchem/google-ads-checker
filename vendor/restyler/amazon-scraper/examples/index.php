<?php
ini_set('display_errors', '1');
require __DIR__ . '/../vendor/autoload.php';

use AmazonScraper\Client;

$client = new Client([
        'rapidapi_key' => 'YOUR-RAPIDAPI-KEY' // get your key on https://apiroad.net/marketplace/apis/tiktok
    ]
);


try {
    #########################
    ### get product details
    #########################
    $response = $client->getProductDetails([
        'asin' => 'B07XQXZXJC'
    ]);
    
    echo '<h2>Product details for asin B07XQXZXJC:</h2><pre>';
    print_r($response);
    echo '</pre>';

    #########################
    ### get product reviews
    #########################
    $response = $client->getProductReviews([
        'sort_by' => 'helpful',
        'page' => 1,
        'asin' => 'B07XQXZXJC'
    ]);
    
    echo '<h2>Product reviews:</h2><pre>';
    print_r($response);
    echo '</pre>';


    
    #########################
    ### search products
    #########################
    $response = $client->searchProducts([
        'query' => 'xbox',
        'country' => 'US',
        'page' => 1
    ]);

    echo '<h2>Video metadata by URL:</h2><pre>';
    print_r($response);
    echo '</pre>';
    exit();




    


    
} catch (GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    
    echo 'Status code: ' . $response->getStatusCode() . "\n";
    echo 'Err message: ' . $e->getMessage() . "\n";
    

}



