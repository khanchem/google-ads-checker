<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\Http;
use App\Models\Urls;
use App\Jobs\Adsense;
use App\Models\Report;
use App\Jobs\URlChecker;
use App\Http\Controllers\GoogleAdsController;
class UrlController extends Controller
{
   public function AddURLs(Request $request)
    {
       // dispatch(new Adsense());
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xls,xlsx',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid file. Please upload a valid Excel file.'], 400);
        }
        
        $excelFile = $request->file('file');
        $excelPath = $excelFile->getRealPath();
        $referenceNumber = mt_rand(100000, 999999);
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
                if (strcasecmp($url, 'url') === 0 || strcasecmp($url, 'URL') === 0) {
                    continue; // Skip this row
                }
                Urls::create([
                    'reference_non' => $referenceNumber,
                    'url' => $url,
                    'status'=>0,
                ]);
                // Add the URL to the array of processed URLs
             
            }
        }
        $reader->close(); // Close the Spout reader
    
        // Return the array of processed URLs as JSON
        $controller = new GoogleAdsController();
        $controller->checkAdSenseTags();
        return response()->json([
            //"success" => "true",
            //"message" => "File is in progress , wait a while",
            'refrenceID' => $referenceNumber
        ]);
        
    }

    public function getUrls($reference)
    {
        $urls = Report::where('reference_no', $reference)->whereNotNull('url')->get();
        $data = [
            'status' => 'checked', // Use => instead of :
    
            'Urls' => $urls
        ];
        return response()->json(['data' => $data]);
    }
    
}
