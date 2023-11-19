<?php

namespace App\Http\Controllers;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use AmazonScraper\Client;
use Illuminate\Support\Facades\Validator;
use PHPExcel_IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Http;
// Get the absolute path to the lib folder
$libPath = realpath(__DIR__ . '/../../lib');

// Require the simple_html_dom.php file from the lib folder
require($libPath . '/simple_html_dom.php');


class AmazonScraperController extends VoyagerBaseController
{
    public function checkAdSenseTags(Request $request)
{
    $validator = Validator::make($request->all(), [
        'excelFile' => 'required|file|mimes:xls,xlsx',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => 'Invalid file. Please upload a valid Excel file.'], 400);
    }

    $excelFile = $request->file('excelFile');
    $excelPath = $excelFile->getRealPath();

    $noAdSenseUrls = [];

// Your existing code here...

// After processing URLs and populating $noAdSenseUrls, remove duplicates:
$noAdSenseUrls = array_unique($noAdSenseUrls);
    // Create a reader for the Excel file using Spout
    $reader = ReaderEntityFactory::createXLSXReader();
    $reader->open($excelPath);

    // Get the "url" column index (assuming the column headers are in the first row)
    $urlColumnIndex = 3; // Adjust if needed

    // Flag to track whether the current row is the first row
    $isFirstRow = true;
$continueProcessing = true; 
    // Iterate through each row in the Excel file
    foreach ($reader->getSheetIterator() as $sheet) {
        foreach ($sheet->getRowIterator() as $row) {
            // Skip the first row (header row)
            
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }
 
            // Get the URL from the "url" column
            $url = $row->getCellAtIndex($urlColumnIndex)->getValue();
           
    if (empty($url)) {
            $continueProcessing = false;
            break; // Exit the inner loop
        }
     
            // Check if "ads.txt" exists on the base URL
            $parsedUrl = parse_url($url);
            if (isset($parsedUrl['scheme'])) {
            $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
             // Construct the base URL
            }

            $adsTxtUrl = $baseUrl . '/ads.txt';
          
                 

            // Check if "ads.txt" exists on the base URL using checkUrlExists
            if (!$this->checkUrlExists($adsTxtUrl)) {
                $curl = curl_init($url);
// Set cURL options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($curl);

curl_close($curl);
$dom = new \simple_html_dom(
    null,
    true,
    true,
    DEFAULT_TARGET_CHARSET,
    true,
    DEFAULT_BR_TEXT,
    DEFAULT_SPAN_TEXT
);
$response=$dom->load($response, true, true);
$articles = array();
                $curl = curl_init($url);
// Set cURL options
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($curl);

curl_close($curl);
$dom = new \simple_html_dom(
    null,
    true,
    true,
    DEFAULT_TARGET_CHARSET,
    true,
    DEFAULT_BR_TEXT,
    DEFAULT_SPAN_TEXT
);
$response = $dom->load($response, true, true);




$keywordsToCheck = ['adsbygoogle.js', 'googleads.g', 'googletagmanager']; // Keywords to check



$foundKeywords = false;

// Find all script, link, and meta tags in the HTML content
$elementsToCheck = array_merge(
    $response->find('script'),
    $response->find('link'),
    $response->find('meta')
);

foreach ($elementsToCheck as $element) {
    if ($element->tag === 'script') {
        // Check script content
        $elementContent = $element->innertext;
    } elseif ($element->tag === 'link') {
        // Check link href attribute
        $elementContent = $element->href;
    } elseif ($element->tag === 'meta') {
        // Check meta content attribute
        $elementContent = $element->content;
    } else {
        continue; // Skip other types of elements
    }

    // Check each keyword within the element content
    foreach ($keywordsToCheck as $keyword) {
        if (strpos($elementContent, $keyword) !== false) {
            $foundKeywords = true;
            break 2; // Break both the inner and outer loops when a keyword is found
        }
    }
}

if ($foundKeywords) {
    // Keywords found in specified HTML elements
    $noAdSenseUrls[] = $url;
}
}else{
                   $noAdSenseUrls[] = $url;
            }
        }
    }

    $reader->close(); // Close the Spout reader
$uniqueUrls = [];
foreach ($noAdSenseUrls as $url) {
    if (!in_array($url, $uniqueUrls)) {
        $uniqueUrls[] = $url;
    }
}

    return response()->json(['noAdSenseUrls' => $uniqueUrls]);
}


// Function to check for "adsbygoogle.js"
private function checkAdsByGoogle($url) {
    $html = file_get_contents($url);

    return strpos($html, 'adsbygoogle.js') !== false;
}
// Function to check if a URL exists
private function checkUrlExists($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return $httpCode === 200;
}
}

