# Simple PHP Amazon scraper
This library is a Guzzle-based wrapper around RapidAPI solution which scrapes Amazon product details and reviews.

Get your access key here:
https://rapidapi.com/restyler/api/amazon23

See /examples folder for examples

## Installation
```
composer require restyler/amazon-scraper
```

## Quick example:
### getProductDetails(): Get product details by ASIN
```php
use AmazonScraper\Client;

$amazonClient = new Client([
        'rapidapi_key' => 'YOUR-RAPIDAPI-KEY'
    ]
);

$response = $amazonClient->getProductDetails([
    'asin' => 'B07XQXZXJC',
    'country' => 'US'
]);

print_r($response);

```

### getProductReviews(): Get product reviews by ASIN
```php
use AmazonScraper\Client;

$amazonClient = new Client([
        'rapidapi_key' => 'YOUR-RAPIDAPI-KEY'
    ]
);

$response = $amazonClient->getProductReviews([
    'asin' => 'B07XQXZXJC',
    'country' => 'US',
    'page' => 1, // pagination starts from page#1
    'sort_by' => 'recent' // 'recent' or 'helpful'
]);

print_r($response);

```

### searchProducts(): Search products by keyword
```php
use AmazonScraper\Client;

$amazonClient = new Client([
        'rapidapi_key' => 'YOUR-RAPIDAPI-KEY'
    ]
);

$response = $amazonClient->searchProducts([
    'query' => 'xbox',
    'country' => 'US',
    'page' => 2, // pagination starts from page#1
]);

print_r($response);

```


## Proxy Support
This RapidAPI API uses a network of proxies so you don't need to manage proxies manually.

## Examples, Error Handling
See full example file [here](examples/index.php).


