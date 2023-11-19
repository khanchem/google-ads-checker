<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use AmazonScraper\Client;
use Illuminate\Support\Facades\Validator;
use PHPExcel_IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Http;
use App\Models\Urls;
use App\Models\Report;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use App\Jobs\URlChecker;
// Get the absolute path to the lib folder
$libPath = realpath(__DIR__ . '/../../lib');

// Require the simple_html_dom.php file from the lib folder
require($libPath . '/simple_html_dom.php');

class GoogleAdsController extends Controller
{ 
    
    public function checkAdSenseTags()
    {
        $urls = Urls::where('status', 0)
    ->whereNotNull('url')
    ->get();

        foreach ($urls as $url) {
        dispatch(new URlChecker($url->id));
      }
        return response()->json(['noAdSenseUrls' => "done"]);
    }
    
    public function process($url){

            $urls = Urls::where('id', $url)
          ->first();

    
       
            $foundAds = false;
            $referenceNumber = $urls->reference_non;
            
            // Check if "ads.txt" exists on the base URL
            $parsedUrl = parse_url($urls->url);
            $baseUrl = '';
            if (isset($parsedUrl['scheme'])) {
                $baseUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
            }
    
            $adsTxtUrl = $baseUrl . '/ads.txt';
            $curl = curl_init($adsTxtUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);
             
            if ($httpCode == 200) {
                $foundAds = true;
            } elseif ($httpCode == 404 || $httpCode == 403 || $httpCode == 302 || $httpCode == 301) {
                $foundKeywords = false; // Initialize $foundKeywords here
              
                
               
                $response =  Browsershot::url($urls->url)
           ->newHeadless()
           ->timeout(90000)
           ->waitUntilNetworkIdle()
           ->bodyHtml();
                
      
               
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
           
                $keywordsToCheck = ['/.*adsbygoogle.*/i', '/.*googleads.*/i', '/.*googlesyndication.*/i']; // Keywords to check
    
                $elementsToCheck = array_merge(
                    $response->find('script'),
                    $response->find('link'),
                    $response->find('meta'),
                    $response->find('a'),
                    $response->find('body')
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
                    } elseif ($element->tag === 'a') {
                        // Check <a> href attribute
                        $elementContent = $element->href;
                    } elseif ($element->tag === 'body') {
                        // Check <a> href attribute
                        $elementContent = $element->innertext;
                     
                    } else {
                        continue; // Skip other types of elements
                    }
    
                    foreach ($keywordsToCheck as $keywordRegex) {
                       
                        if (preg_match($keywordRegex, $elementContent)) {
                            $foundAds = true;
                            break 2; // Break both the inner and outer loops when a keyword is found
                        }
                    }
                }
                $dom->clear();
            } else {
                $foundAds = false;
            }
    
            $status = $foundAds ? 'true' : 'false';
         
                Report::create([
                    'url' => $urls->url,
                    'reference_no' => $referenceNumber,
                    'googleAds_status' => $status
                ]);
            
            
           
    
            Urls::where('reference_non', $referenceNumber)->update([
                'status' => 1,
            ]);
        
    }
    
    
    

   
}
